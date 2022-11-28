import{R as J,u as K,e as O,aM as q,x as W,I as P,Q as T,F as Y,r as d,o as m,n as k,w as l,m as s,a as i,q as B,t as b,U as V,g as U,l as M,h as N,i as A,z as Z,k as ee,b as u,c as R,a9 as H,aa as z,E as G,s as te,H as ae,L as se}from"./main.d0ffa6eb.js";const oe={props:{row:{type:Object,default:null},table:{type:Object,default:null},loadData:{type:Function,default:null}},setup(S){const p=S,e=J();K();const{t:f}=O(),_=q(),w=W(),g=P(),C=T();Y("utils");async function c(h){Promise.all([await _.fetchAbilities(),await _.fetchRole(h)]).then(()=>{C.openModal({title:f("settings.roles.edit_role"),componentName:"RolesModal",size:"lg",refreshData:p.loadData})})}async function I(h){e.openDialog({title:f("general.are_you_sure"),message:f("settings.roles.confirm_delete"),yesLabel:f("general.ok"),noLabel:f("general.cancel"),variant:"danger",hideNoButton:!1,size:"lg"}).then(async r=>{r&&await _.deleteRole(h).then(v=>{v.data&&p.loadData&&p.loadData()})})}return(h,r)=>{const v=d("BaseIcon"),y=d("BaseButton"),t=d("BaseDropdownItem"),a=d("BaseDropdown");return m(),k(a,null,{activator:l(()=>[s(w).name==="roles.view"?(m(),k(y,{key:0,variant:"primary"},{default:l(()=>[i(v,{name:"DotsHorizontalIcon",class:"h-5 text-white"})]),_:1})):(m(),k(v,{key:1,name:"DotsHorizontalIcon",class:"h-5 text-gray-500"}))]),default:l(()=>[s(g).currentUser.is_owner?(m(),k(t,{key:0,onClick:r[0]||(r[0]=n=>c(S.row.id))},{default:l(()=>[i(v,{name:"PencilIcon",class:"w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"}),B(" "+b(h.$t("general.edit")),1)]),_:1})):V("",!0),s(g).currentUser.is_owner?(m(),k(t,{key:1,onClick:r[1]||(r[1]=n=>I(S.row.id))},{default:l(()=>[i(v,{name:"TrashIcon",class:"w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-500"}),B(" "+b(h.$t("general.delete")),1)]),_:1})):V("",!0)]),_:1})}}},ne={class:"flex justify-between w-full"},le=["onSubmit"],ie={class:"px-4 md:px-8 py-4 md:py-6"},re={class:"flex justify-between"},de={class:"text-sm not-italic font-medium text-primary-800 px-4 md:px-8 py-1.5"},ce=u("span",{class:"text-sm text-red-500"}," *",-1),ue={class:"text-sm not-italic font-medium text-gray-300 px-4 md:px-8 py-1.5"},me=B(" / "),pe={class:"border-t border-gray-200 py-3"},fe={class:"grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 px-8 sm:px-8"},be={class:"text-sm text-gray-500 border-b border-gray-200 pb-1 mb-2"},_e={key:0,class:"block mt-0.5 text-sm text-red-500"},ge={class:"z-0 flex justify-end p-4 border-t border-solid border--200 border-modal-bg"},ye={setup(S){const p=T(),e=q(),{t:f}=O();let _=U(!1),w=U(!1);const g=M(()=>p.active&&p.componentName==="RolesModal"),C=M(()=>({name:{required:N.withMessage(f("validation.required"),A),minLength:N.withMessage(f("validation.name_min_length",{count:3}),Z(3))},abilities:{required:N.withMessage(f("validation.at_least_one_ability"),A)}})),c=ee(C,M(()=>e.currentRole));async function I(){if(c.value.$touch(),c.value.$invalid)return!0;try{const t=e.isEdit?e.updateRole:e.addRole;_.value=!0,await t(e.currentRole),_.value=!1,p.refreshData&&p.refreshData(),y()}catch{return _.value=!1,!0}}function h(t){if(!e.currentRole.abilities.find(n=>n.ability===t.ability)&&t?.depends_on?.length){v(t);return}t?.depends_on?.forEach(n=>{Object.keys(e.abilitiesList).forEach(o=>{e.abilitiesList[o].forEach($=>{n===$.ability&&($.disabled=!0,e.currentRole.abilities.find(L=>L.ability===n)||e.currentRole.abilities.push($))})})})}function r(t){let a=[];Object.keys(e.abilitiesList).forEach(n=>{e.abilitiesList[n].forEach(o=>{o?.depends_on&&(a=[...a,...o.depends_on])})}),Object.keys(e.abilitiesList).forEach(n=>{e.abilitiesList[n].forEach(o=>{a.includes(o.ability)&&(t?o.disabled=!0:o.disabled=!1),e.currentRole.abilities.push(o)})}),t||(e.currentRole.abilities=[])}function v(t){t.depends_on.forEach(a=>{Object.keys(e.abilitiesList).forEach(n=>{e.abilitiesList[n].forEach(o=>{let $=e.currentRole.abilities.find(D=>D.depends_on?.includes(o.ability));a===o.ability&&!$&&(o.disabled=!1)})})})}function y(){p.closeModal(),setTimeout(()=>{e.currentRole={id:null,name:"",abilities:[]},Object.keys(e.abilitiesList).forEach(t=>{e.abilitiesList[t].forEach(a=>{a.disabled=!1})}),c.value.$reset()},300)}return(t,a)=>{const n=d("BaseIcon"),o=d("BaseInput"),$=d("BaseInputGroup"),D=d("BaseCheckbox"),L=d("BaseButton"),Q=d("BaseModal");return m(),k(Q,{show:s(g),onClose:y},{header:l(()=>[u("div",ne,[B(b(s(p).title)+" ",1),i(n,{name:"XIcon",class:"w-6 h-6 text-gray-500 cursor-pointer",onClick:y})])]),default:l(()=>[u("form",{onSubmit:te(I,["prevent"])},[u("div",ie,[i($,{label:t.$t("settings.roles.name"),class:"mt-3",error:s(c).name.$error&&s(c).name.$errors[0].$message,required:"","content-loading":s(w)},{default:l(()=>[i(o,{modelValue:s(e).currentRole.name,"onUpdate:modelValue":a[0]||(a[0]=x=>s(e).currentRole.name=x),invalid:s(c).name.$error,type:"text","content-loading":s(w),onInput:a[1]||(a[1]=x=>s(c).name.$touch())},null,8,["modelValue","invalid","content-loading"])]),_:1},8,["label","error","content-loading"])]),u("div",re,[u("h6",de,[B(b(t.$tc("settings.roles.permission",2))+" ",1),ce]),u("div",ue,[u("a",{class:"cursor-pointer text-primary-400",onClick:a[2]||(a[2]=x=>r(!0))},b(t.$t("settings.roles.select_all")),1),me,u("a",{class:"cursor-pointer text-primary-400",onClick:a[3]||(a[3]=x=>r(!1))},b(t.$t("settings.roles.none")),1)])]),u("div",pe,[u("div",fe,[(m(!0),R(z,null,H(s(e).abilitiesList,(x,j)=>(m(),R("div",{key:j,class:"flex flex-col space-y-1"},[u("p",be,b(j==="Estimate"?"Quotation":j),1),(m(!0),R(z,null,H(x,(E,X)=>(m(),R("div",{key:X,class:"flex"},[i(D,{modelValue:s(e).currentRole.abilities,"onUpdate:modelValue":[a[4]||(a[4]=F=>s(e).currentRole.abilities=F),F=>h(E)],"set-initial-value":!0,variant:"primary",disabled:E.disabled,label:E.name,value:E},null,8,["modelValue","disabled","label","value","onUpdate:modelValue"])]))),128))]))),128)),s(c).abilities.$error?(m(),R("span",_e,b(s(c).abilities.$errors[0].$message),1)):V("",!0)])]),u("div",ge,[i(L,{class:"mr-3 text-sm",variant:"primary-outline",type:"button",onClick:y},{default:l(()=>[B(b(t.$t("general.cancel")),1)]),_:1}),i(L,{loading:s(_),disabled:s(_),variant:"primary",type:"submit"},{left:l(x=>[i(n,{name:"SaveIcon",class:G(x.class)},null,8,["class"])]),default:l(()=>[B(" "+b(s(e).isEdit?t.$t("general.update"):t.$t("general.save")),1)]),_:1},8,["loading","disabled"])])],40,le)]),_:1},8,["show"])}}},ve={setup(S){const p=T(),e=q(),f=P(),_=ae(),{t:w}=O(),g=U(null),C=M(()=>[{key:"name",label:w("settings.roles.role_name"),thClass:"extra",tdClass:"font-medium text-gray-900"},{key:"created_at",label:w("settings.roles.added_on"),tdClass:"font-medium text-gray-900"},{key:"actions",label:"",tdClass:"text-right text-sm font-medium",sortable:!1}]);async function c({page:r,filter:v,sort:y}){let t={orderByField:y.fieldName||"created_at",orderBy:y.order||"desc",company_id:_.selectedCompany.id};return{data:(await e.fetchRoles(t)).data.data}}async function I(){g.value&&g.value.refresh()}async function h(){await e.fetchAbilities(),p.openModal({title:w("settings.roles.add_role"),componentName:"RolesModal",size:"lg",refreshData:g.value&&g.value.refresh})}return(r,v)=>{const y=d("BaseIcon"),t=d("BaseButton"),a=d("BaseTable"),n=d("BaseSettingCard");return m(),R(z,null,[i(ye),i(n,{title:r.$t("settings.roles.title"),description:r.$t("settings.roles.description")},se({default:l(()=>[i(a,{ref_key:"table",ref:g,data:c,columns:s(C),class:"mt-14"},{"cell-created_at":l(({row:o})=>[B(b(o.data.formatted_created_at),1)]),"cell-actions":l(({row:o})=>[s(f).currentUser.is_owner&&o.data.name!=="super admin"?(m(),k(oe,{key:0,row:o.data,table:g.value,"load-data":I},null,8,["row","table"])):V("",!0)]),_:1},8,["columns"])]),_:2},[s(f).currentUser.is_owner?{name:"action",fn:l(()=>[i(t,{variant:"primary-outline",onClick:h},{left:l(o=>[i(y,{name:"PlusIcon",class:G(o.class)},null,8,["class"])]),default:l(()=>[B(" "+b(r.$t("settings.roles.add_new_role")),1)]),_:1})])}:void 0]),1032,["title","description"])],64)}}};export{ve as default};
