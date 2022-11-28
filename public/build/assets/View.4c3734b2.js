import{Q as Y,aq as Z,u as ee,I as te,R as ae,e as se,F as oe,g as u,x as re,f as le,y as ne,l as h,K as ie,ab as de,r as l,o as c,c as v,a as s,n as p,w as r,b as n,m as o,J as ce,q as V,t as b,U as y,aa as N,a9 as ue,E as me}from"./main.d0ffa6eb.js";import{_ as fe}from"./DebitNoteIndexDropdown.dd359044.js";import{_ as _e}from"./SendInvoiceModal.67cc3524.js";import{L as pe}from"./LoadingIcon.272413e7.js";const be={class:"text-sm mr-3"},ye={class:"fixed top-0 left-0 hidden h-full pt-16 pb-4 ml-56 bg-white xl:ml-64 w-88 xl:block"},ge={class:"flex items-center justify-between px-4 pt-8 pb-2 border border-gray-200 border-solid height-full"},he={class:"mb-6"},ve={class:"flex mb-6 ml-3",role:"group","aria-label":"First group"},xe={class:"px-2 py-1 pb-2 mb-1 mb-2 text-sm border-b border-gray-200 border-solid"},Be={key:0,class:"h-full pb-32 overflow-y-scroll border-l border-gray-200 border-solid base-scroll"},ke={class:"flex-2"},Ie={class:"mt-1 mb-2 text-xs not-italic font-medium leading-5 text-gray-600"},we={class:"flex-1 whitespace-nowrap right"},De={class:"text-sm not-italic font-normal leading-5 text-right text-gray-600 est-date"},Se={class:"flex justify-center p-4 items-center"},Te={key:0,class:"flex justify-center px-4 mt-5 text-sm text-gray-600"},Ee={class:"flex flex-col min-h-0 mt-8 overflow-hidden",style:{height:"75vh"}},$e=["src"],Re={setup(Fe){Y();const m=Z();ee();const C=te(),A=ae(),{t:_}=se();oe("$utils"),u(null),u(null);const i=u(null);u(null);const x=re(),R=le();u(["DRAFT","SENT","VIEWED","EXPIRED","ACCEPTED","REJECTED"]);const k=u(!1);u(!1),u(!1);const w=u(!1),g=u(!1),t=ne({orderBy:null,orderByField:null,searchText:null}),L=h(()=>i.value.debit_number),D=h(()=>t.orderBy==="asc"||t.orderBy==null);h(()=>D.value?_("general.ascending"):_("general.descending"));const z=h(()=>(console.log(`/debits/pdf/${i.value.unique_hash}`),`/debits/pdf/${i.value.unique_hash}`));h(()=>i.value&&i.value.id?invoice.value.id:null),ie(x,(e,d)=>{e.name==="debit.view"&&T()});async function P(){A.openDialog({title:_("general.are_you_sure"),message:_("debitnote.debitnote_mark_as_sent"),yesLabel:_("general.ok"),noLabel:_("general.cancel"),variant:"primary",hideNoButton:!1,size:"lg"}).then(async e=>{k.value=!1,e&&(await m.markAsSent({id:i.value.id,status:"SENT"}),i.value.status="SENT",k.value=!0,R.go(0))})}function U(e){return x.params.id==e}async function S(){g.value=!0,await m.fetchDebitNotes(),g.value=!1,setTimeout(()=>{j()},500)}function j(){const e=document.getElementById(`debit-${x.params.id}`);e&&(e.scrollIntoView({behavior:"smooth"}),e.classList.add("shake"))}async function T(){let e=await m.fetchDebitNote(x.params.id);e.data&&(i.value={...e.data.data})}async function f(){let e="";t.searchText!==""&&t.searchText!==null&&t.searchText!==void 0&&(e+=`search=${t.searchText}&`),t.orderBy!==null&&t.orderBy!==void 0&&(e+=`orderBy=${t.orderBy}&`),t.orderByField!==null&&t.orderByField!==void 0&&(e+=`orderByField=${t.orderByField}`),w.value=!0;let d=await m.searchDebitNotes(e);w.value=!1,d.data&&(m.invoices=d.data.data)}function M(){return t.orderBy==="asc"?(t.orderBy="desc",f(),!0):(t.orderBy="asc",f(),!0)}return S(),T(),f=de.exports.debounce(f,500),(e,d)=>{const I=l("BaseButton"),q=l("BasePageHeader"),B=l("BaseIcon"),G=l("BaseInput"),E=l("BaseRadio"),$=l("BaseInputGroup"),F=l("BaseDropdownItem"),H=l("BaseDropdown"),J=l("BaseText"),O=l("BaseEstimateStatusBadge"),K=l("BaseFormatMoney"),Q=l("router-link"),W=l("BasePage");return c(),v(N,null,[s(_e),i.value?(c(),p(W,{key:0,class:"xl:pl-96 xl:ml-8"},{default:r(()=>[s(q,{title:o(L)},{actions:r(()=>[n("div",be,[i.value.is_edit=="1"&&i.value.status==="DRAFT"&&o(C).hasAbilities(o(ce).EDIT_INVOICE)?(c(),p(I,{key:0,disabled:k.value,variant:"primary-outline",onClick:P},{default:r(()=>[V(b(e.$t("invoices.mark_as_sent")),1)]),_:1},8,["disabled"])):y("",!0)]),s(fe,{class:"ml-3",row:i.value,"load-data":S},null,8,["row"])]),_:1},8,["title"]),n("div",ye,[n("div",ge,[n("div",he,[s(G,{modelValue:o(t).searchText,"onUpdate:modelValue":d[0]||(d[0]=a=>o(t).searchText=a),placeholder:e.$t("general.search"),type:"text",variant:"gray",onInput:d[1]||(d[1]=a=>f())},{right:r(()=>[s(B,{name:"SearchIcon",class:"h-5 text-gray-400"})]),_:1},8,["modelValue","placeholder"])]),n("div",ve,[s(H,{class:"ml-3",position:"bottom-start"},{activator:r(()=>[s(I,{size:"md",variant:"gray"},{default:r(()=>[s(B,{name:"FilterIcon"})]),_:1})]),default:r(()=>[n("div",xe,b(e.$t("general.sort_by")),1),s(F,{class:"flex px-1 py-2 cursor-pointer"},{default:r(()=>[s($,{class:"-mt-3 font-normal"},{default:r(()=>[s(E,{id:"filter_debit_date",modelValue:o(t).orderByField,"onUpdate:modelValue":[d[2]||(d[2]=a=>o(t).orderByField=a),f],label:e.$t("debits.date"),size:"sm",name:"filter",value:"debit_date"},null,8,["modelValue","label"])]),_:1})]),_:1}),s(F,{class:"flex px-1 py-2 cursor-pointer"},{default:r(()=>[s($,{class:"-mt-3 font-normal"},{default:r(()=>[s(E,{id:"filter_debit_number",modelValue:o(t).orderByField,"onUpdate:modelValue":[d[3]||(d[3]=a=>o(t).orderByField=a),f],label:e.$t("debits.number"),value:"debit_number",size:"sm",name:"filter"},null,8,["modelValue","label"])]),_:1})]),_:1})]),_:1}),s(I,{class:"ml-1",size:"md",variant:"gray",onClick:M},{default:r(()=>[o(D)?(c(),p(B,{key:0,name:"SortAscendingIcon"})):(c(),p(B,{key:1,name:"SortDescendingIcon"}))]),_:1})])]),o(m)&&o(m).invoices?(c(),v("div",Be,[(c(!0),v(N,null,ue(o(m).invoices,(a,X)=>(c(),v("div",{key:X},[a&&!g.value?(c(),p(Q,{key:0,id:"invoice-"+a.id,to:`/admin/debit-note/${a.id}/view`,class:me(["flex justify-between side-invoice p-4 cursor-pointer hover:bg-gray-100 items-center border-l-4 border-transparent",{"bg-gray-100 border-l-4 border-primary-500 border-solid":U(a.id)}]),style:{"border-bottom":"1px solid rgba(185, 193, 209, 0.41)"}},{default:r(()=>[n("div",ke,[s(J,{text:a.customer.name,length:30,class:"pr-2 mb-2 text-sm not-italic font-normal leading-5 text-black capitalize truncate"},null,8,["text"]),n("div",Ie,b(a.debit_number),1),s(O,{status:a.status,class:"px-1 text-xs"},{default:r(()=>[V(b(a.status),1)]),_:2},1032,["status"])]),n("div",we,[s(K,{class:"mb-2 text-xl not-italic font-semibold leading-8 text-right text-gray-900 block",amount:a.total,currency:a.customer.currency},null,8,["amount","currency"]),n("div",De,b(a.formatted_debit_date),1)])]),_:2},1032,["id","to","class"])):y("",!0)]))),128)),n("div",Se,[g.value?(c(),p(pe,{key:0,class:"h-6 m-1 animate-spin text-primary-400"})):y("",!0)]),!o(m).invoices.length&&!g.value?(c(),v("p",Te,b(e.$t("debits.no_matching_invoices")),1)):y("",!0)])):y("",!0)]),n("div",Ee,[n("iframe",{src:`${o(z)}`,class:"flex-1 border border-gray-400 border-solid bg-white rounded-md frame-style"},null,8,$e)])]),_:1})):y("",!0)],64)}}};export{Re as default};
