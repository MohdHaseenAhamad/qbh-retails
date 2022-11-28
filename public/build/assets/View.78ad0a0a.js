import{Q as J,al as K,u as O,I as Q,R as W,e as X,F as Y,g as d,x as Z,f as ee,y as te,l as _,K as ae,ab as se,r,o as c,c as h,a as s,n as y,w as o,m as l,b as i,t as g,aa as S,a9 as re,E as oe,q as le,U as b}from"./main.d0ffa6eb.js";import{_ as ne}from"./PurchaseIndexDropdown.94937fe6.js";import{_ as ie}from"./SendInvoiceModal.67cc3524.js";import{L as de}from"./LoadingIcon.272413e7.js";const ce={class:"fixed top-0 left-0 hidden h-full pt-16 pb-4 ml-56 bg-white xl:ml-64 w-88 xl:block"},ue={class:"flex items-center justify-between px-4 pt-8 pb-2 border border-gray-200 border-solid height-full"},me={class:"mb-6"},pe={class:"flex mb-6 ml-3",role:"group","aria-label":"First group"},fe={class:"px-2 py-1 pb-2 mb-1 mb-2 text-sm border-b border-gray-200 border-solid"},_e={key:0,class:"h-full pb-32 overflow-y-scroll border-l border-gray-200 border-solid base-scroll"},he={class:"flex-2"},ye={class:"mt-1 mb-2 text-xs not-italic font-medium leading-5 text-gray-600"},ge={class:"flex-1 whitespace-nowrap right"},be={class:"text-sm not-italic font-normal leading-5 text-right text-gray-600 est-date"},xe={class:"flex justify-center p-4 items-center"},Be={key:0,class:"flex justify-center px-4 mt-5 text-sm text-gray-600"},ve={class:"flex flex-col min-h-0 mt-8 overflow-hidden",style:{height:"75vh"}},we=["src"],De={setup(Ie){J();const u=K();O(),Q(),W();const{t:v}=X();Y("$utils"),d(null),d(null);const m=d(null);d(null);const x=Z();ee(),d(["DRAFT","SENT","VIEWED","EXPIRED","ACCEPTED","REJECTED"]),d(!1),d(!1),d(!1);const w=d(!1),f=d(!1),e=te({orderBy:null,orderByField:null,searchText:null}),V=_(()=>m.value.purchase_no),I=_(()=>e.orderBy==="asc"||e.orderBy==null);_(()=>I.value?v("general.ascending"):v("general.descending"));const P=_(()=>(console.log(`/purchases/pdf/${m.value.unique_hash}`),`/purchases/pdf/${m.value.unique_hash}`));_(()=>m.value&&m.value.id?purchase.value.id:null),ae(x,(t,n)=>{t.name==="purchases.view"&&T()});function C(t){return x.params.id==t}async function k(){f.value=!0,await u.fetchPurchases(),f.value=!1,setTimeout(()=>{R()},500)}function R(){const t=document.getElementById(`purchase-${x.params.id}`);t&&(t.scrollIntoView({behavior:"smooth"}),t.classList.add("shake"))}async function T(){let t=await u.fetchPurchase(x.params.id);t.data&&(m.value={...t.data.data})}async function p(){let t="";e.searchText!==""&&e.searchText!==null&&e.searchText!==void 0&&(t+=`search=${e.searchText}&`),e.orderBy!==null&&e.orderBy!==void 0&&(t+=`orderBy=${e.orderBy}&`),e.orderByField!==null&&e.orderByField!==void 0&&(t+=`orderByField=${e.orderByField}`),w.value=!0;let n=await u.searchPurchase(t);w.value=!1,n.data&&(u.purchases=n.data.data)}function z(){return e.orderBy==="asc"?(e.orderBy="desc",p(),!0):(e.orderBy="asc",p(),!0)}return k(),T(),p=se.exports.debounce(p,500),(t,n)=>{const L=r("BasePageHeader"),B=r("BaseIcon"),N=r("BaseInput"),$=r("BaseButton"),F=r("BaseRadio"),D=r("BaseInputGroup"),E=r("BaseDropdownItem"),U=r("BaseDropdown"),j=r("BaseText"),A=r("BaseEstimateStatusBadge"),q=r("BaseFormatMoney"),M=r("router-link"),G=r("BasePage");return c(),h(S,null,[s(ie),m.value?(c(),y(G,{key:0,class:"xl:pl-96 xl:ml-8"},{default:o(()=>[s(L,{title:l(V)},{actions:o(()=>[s(ne,{class:"ml-3",row:m.value,"load-data":k},null,8,["row"])]),_:1},8,["title"]),i("div",ce,[i("div",ue,[i("div",me,[s(N,{modelValue:l(e).searchText,"onUpdate:modelValue":n[0]||(n[0]=a=>l(e).searchText=a),placeholder:t.$t("general.search"),type:"text",variant:"gray",onInput:n[1]||(n[1]=a=>p())},{right:o(()=>[s(B,{name:"SearchIcon",class:"h-5 text-gray-400"})]),_:1},8,["modelValue","placeholder"])]),i("div",pe,[s(U,{class:"ml-3",position:"bottom-start"},{activator:o(()=>[s($,{size:"md",variant:"gray"},{default:o(()=>[s(B,{name:"FilterIcon"})]),_:1})]),default:o(()=>[i("div",fe,g(t.$t("general.sort_by")),1),s(E,{class:"flex px-1 py-2 cursor-pointer"},{default:o(()=>[s(D,{class:"-mt-3 font-normal"},{default:o(()=>[s(F,{id:"filter_invoice_date",modelValue:l(e).orderByField,"onUpdate:modelValue":[n[2]||(n[2]=a=>l(e).orderByField=a),p],label:t.$t("purchases.purchase_date"),size:"sm",name:"filter",value:"purchase_date"},null,8,["modelValue","label"])]),_:1})]),_:1}),s(E,{class:"flex px-1 py-2 cursor-pointer"},{default:o(()=>[s(D,{class:"-mt-3 font-normal"},{default:o(()=>[s(F,{id:"filter_purchase_no",modelValue:l(e).orderByField,"onUpdate:modelValue":[n[3]||(n[3]=a=>l(e).orderByField=a),p],label:t.$t("purchases.number"),value:"purchase_no",size:"sm",name:"filter"},null,8,["modelValue","label"])]),_:1})]),_:1})]),_:1}),s($,{class:"ml-1",size:"md",variant:"gray",onClick:z},{default:o(()=>[l(I)?(c(),y(B,{key:0,name:"SortAscendingIcon"})):(c(),y(B,{key:1,name:"SortDescendingIcon"}))]),_:1})])]),l(u)&&l(u).purchases?(c(),h("div",_e,[(c(!0),h(S,null,re(l(u).purchases,(a,H)=>(c(),h("div",{key:H},[a&&!f.value?(c(),y(M,{key:0,id:"purchase-"+a.id,to:`/admin/purchases/${a.id}/view`,class:oe(["flex justify-between side-invoice p-4 cursor-pointer hover:bg-gray-100 items-center border-l-4 border-transparent",{"bg-gray-100 border-l-4 border-primary-500 border-solid":C(a.id)}]),style:{"border-bottom":"1px solid rgba(185, 193, 209, 0.41)"}},{default:o(()=>[i("div",he,[s(j,{text:a.supplier.name,length:30,class:"pr-2 mb-2 text-sm not-italic font-normal leading-5 text-black capitalize truncate"},null,8,["text"]),i("div",ye,g(a.purchase_no),1),s(A,{status:a.status,class:"px-1 text-xs"},{default:o(()=>[le(g(a.status),1)]),_:2},1032,["status"])]),i("div",ge,[s(q,{class:"mb-2 text-xl not-italic font-semibold leading-8 text-right text-gray-900 block",amount:a.total,currency:a.supplier.currency},null,8,["amount","currency"]),i("div",be,g(a.formatted_purchase_date),1)])]),_:2},1032,["id","to","class"])):b("",!0)]))),128)),i("div",xe,[f.value?(c(),y(de,{key:0,class:"h-6 m-1 animate-spin text-primary-400"})):b("",!0)]),!l(u).purchases.length&&!f.value?(c(),h("p",Be,g(t.$t("purchases.no_matching_purchases")),1)):b("",!0)])):b("",!0)]),i("div",ve,[i("iframe",{src:`${l(P)}`,class:"flex-1 border border-gray-400 border-solid bg-white rounded-md frame-style"},null,8,we)])]),_:1})):b("",!0)],64)}}};export{De as default};