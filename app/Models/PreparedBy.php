<?php

namespace Crater\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class PreparedBy extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

	protected $guarded = [];
    protected $appends = ['signature_image'];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function creator()
    {
        return $this->belongsTo('Crater\Models\User', 'creator_id');
    }

    public function getSignatureImageAttribute()
    {
        $signature = $this->getMedia('signature')->first();
        $isSystem = FileDisk::whereSetAsDefault(true)->first()->isSystem();

        if ($signature) {
            if ($isSystem) {
                return $signature->getPath();
            } else {
                return $signature->getFullUrl();
            }
        }

        return null;
    }
    // public function scopeWhereSearch($query, $search)
    // {
    //     return $query->where('items.name', 'LIKE', '%'.$search.'%');
    // }
    
    public static function createPreparedBy($request)
    {
        $data['name'] = $request->name;
        $data['designation'] = $request->designation;
        $data['contact_number'] = $request->contact_number;
        $data['email'] = $request->email;
        $data['company_id'] = $request->header('company');
        $data['creator_id'] = Auth::id();
        $item = self::create($data);
        // dd($data);
        if ($item && $request->hasFile('signature')) {
            $item->clearMediaCollection('signature');

            $item->addMediaFromRequest('signature')
                ->usingFileName(rand(111111,99999).md5(microtime(true)).'.'.$request->file('signature')->extension())
                ->toMediaCollection('signature');
        }

        // if ($item && $request->has('signature')) {
        //     $data = json_decode($request->signature);
        //     // dd($request->signature);
        //     $item->clearMediaCollection('signature');

        //     $item->addMediaFromBase64($data->data)
        //         ->usingFileName($data->name)
        //         ->toMediaCollection('signature');
        // }


        // return new UserResource($user);
        return $item;
    }

    public function updatePreparedBy($request)
    {
        $this->update($request->all());

        if ($request->hasFile('signature')) {
            $this->clearMediaCollection('signature');

            $this->addMediaFromRequest('signature')
            ->usingFileName(rand(111111,99999).md5(microtime(true)).'.'.$request->file('signature')->extension())
                ->toMediaCollection('signature');
        }

        return PreparedBy::find($this->id);
    }

    public function scopeWhereSearch($query, $search)
    {
        return $query->where('prepared_bies.name', 'LIKE', '%'.$search.'%');
    }

    public function scopePaginateData($query, $limit)
    {
        if ($limit == 'all') {
            return $query->get();
        }

        return $query->paginate($limit);
    }

    public function scopeWhereCompany($query)
    {
        $query->where('prepared_bies.company_id', request()->header('company'));
    }

    public function scopeApplyFilters($query, array $filters)
    {
        $filters = collect($filters);

        if ($filters->get('search')) {
            $query->whereSearch($filters->get('search'));
        }

        if ($filters->get('item_id')) {
            $query->whereItem($filters->get('item_id'));
        }

        if ($filters->get('orderByField') || $filters->get('orderBy')) {
            $field = $filters->get('orderByField') ? $filters->get('orderByField') : 'name';
            $orderBy = $filters->get('orderBy') ? $filters->get('orderBy') : 'asc';
            $query->whereOrder($field, $orderBy);
        }
    }
}
