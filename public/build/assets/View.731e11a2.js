import{ac as V,x as L,e as O,g as S,y as A,ab as J,l as B,r as i,o,c as $,b as E,a as t,w as a,m as e,t as h,n as u,aa as U,a9 as j,U as g,E as z,q as k,H as K,F as H,K as Q,R as W,I as Z,f as ee,J as v,S as te,T as ae}from"./main.d0ffa6eb.js";import{L as se}from"./LoadingIcon.272413e7.js";import"./Chart.b01f5b76.js";import{_ as ne}from"./SupplierIndexDropdown.85f182b8.js";const oe={class:"fixed top-0 left-0 hidden h-full pt-16 pb-4 ml-56 bg-white xl:ml-64 w-88 xl:block"},le={class:"flex items-center justify-between px-4 pt-8 pb-2 border border-gray-200 border-solid height-full"},re={class:"flex mb-6 ml-3",role:"group","aria-label":"First group"},ie={class:"px-4 py-3 pb-2 mb-2 text-sm border-b border-gray-200 border-solid"},ce={class:"px-2"},de={class:"px-2"},ue={class:"h-full pb-32 overflow-y-scroll border-l border-gray-200 border-solid sidebar base-scroll"},pe={class:"flex-1 font-bold text-right whitespace-nowrap"},me={class:"flex justify-center p-4 items-center"},_e={key:0,class:"flex justify-center px-4 mt-5 text-sm text-gray-600"},fe={setup(F){const y=V(),s=L(),{t:p}=O();let c=S(!1),l=S(!1),n=A({orderBy:"",orderByField:"",searchText:""});b=J.exports.debounce(b,500);const w=B(()=>n.orderBy==="asc"||n.orderBy==null);B(()=>w.value?p("general.ascending"):p("general.descending"));function d(r){return s.params.id==r}async function x(){l.value=!0,await y.fetchSuppliers({limit:"all"}),l.value=!1,setTimeout(()=>{m()},500)}function m(){const r=document.getElementById(`customer-${s.params.id}`);r&&(r.scrollIntoView({behavior:"smooth"}),r.classList.add("shake"))}async function b(){let r={};n.searchText!==""&&n.searchText!==null&&n.searchText!==void 0&&(r.display_name=n.searchText),n.orderBy!==null&&n.orderBy!==void 0&&(r.orderBy=n.orderBy),n.orderByField!==null&&n.orderByField!==void 0&&(r.orderByField=n.orderByField),c.value=!0;try{let _=await y.fetchSuppliers(r);c.value=!1,_.data&&(y.suppliers=_.data.data)}catch{c.value=!1}}function I(){return n.orderBy==="asc"?(n.orderBy="desc",b(),!0):(n.orderBy="asc",b(),!0)}return x(),(r,_)=>{const T=i("BaseIcon"),R=i("BaseInput"),D=i("BaseButton"),C=i("BaseRadio"),N=i("BaseInputGroup"),P=i("BaseDropdownItem"),Y=i("BaseDropdown"),M=i("BaseText"),G=i("BaseFormatMoney"),X=i("router-link");return o(),$("div",oe,[E("div",le,[t(R,{modelValue:e(n).searchText,"onUpdate:modelValue":_[0]||(_[0]=f=>e(n).searchText=f),placeholder:r.$t("general.search"),"container-class":"mb-6",type:"text",variant:"gray",onInput:_[1]||(_[1]=f=>b())},{default:a(()=>[t(T,{name:"SearchIcon",class:"text-gray-500"})]),_:1},8,["modelValue","placeholder"]),E("div",re,[t(Y,{"close-on-select":!1,position:"bottom-start","width-class":"w-40","position-class":"left-0"},{activator:a(()=>[t(D,{variant:"gray"},{default:a(()=>[t(T,{name:"FilterIcon"})]),_:1})]),default:a(()=>[E("div",ie,h(r.$t("general.sort_by")),1),E("div",ce,[t(P,{class:"flex px-1 py-2 mt-1 cursor-pointer hover:rounded-md"},{default:a(()=>[t(N,{class:"pt-2 -mt-4"},{default:a(()=>[t(C,{id:"filter_create_date",modelValue:e(n).orderByField,"onUpdate:modelValue":[_[2]||(_[2]=f=>e(n).orderByField=f),b],label:r.$t("customers.create_date"),size:"sm",name:"filter",value:"invoices.created_at"},null,8,["modelValue","label"])]),_:1})]),_:1})]),E("div",de,[t(P,{class:"flex px-1 cursor-pointer hover:rounded-md"},{default:a(()=>[t(N,{class:"pt-2 -mt-4"},{default:a(()=>[t(C,{id:"filter_display_name",modelValue:e(n).orderByField,"onUpdate:modelValue":[_[3]||(_[3]=f=>e(n).orderByField=f),b],label:r.$t("customers.display_name"),size:"sm",name:"filter",value:"name"},null,8,["modelValue","label"])]),_:1})]),_:1})])]),_:1}),t(D,{class:"ml-1",size:"md",variant:"gray",onClick:I},{default:a(()=>[e(w)?(o(),u(T,{key:0,name:"SortAscendingIcon"})):(o(),u(T,{key:1,name:"SortDescendingIcon"}))]),_:1})])]),E("div",ue,[(o(!0),$(U,null,j(e(y).suppliers,(f,q)=>(o(),$("div",{key:q},[f&&!e(l)?(o(),u(X,{key:0,id:"supplier-"+f.id,to:`/admin/suppliers/${f.id}/view`,class:z(["flex justify-between p-4 items-center cursor-pointer hover:bg-gray-100 border-l-4 border-transparent",{"bg-gray-100 border-l-4 border-primary-500 border-solid":d(f.id)}]),style:{"border-top":"1px solid rgba(185, 193, 209, 0.41)"}},{default:a(()=>[E("div",null,[t(M,{text:f.name,length:30,class:"pr-2 text-sm not-italic font-normal leading-5 text-black capitalize truncate"},null,8,["text"]),f.contact_name?(o(),u(M,{key:0,text:f.contact_name,length:30,class:"mt-1 text-xs not-italic font-medium leading-5 text-gray-600"},null,8,["text"])):g("",!0)]),E("div",pe,[t(G,{amount:f.due_amount,currency:f.currency},null,8,["amount","currency"])])]),_:2},1032,["id","to","class"])):g("",!0)]))),128)),E("div",me,[e(l)?(o(),u(se,{key:0,class:"h-6 m-1 animate-spin text-primary-400"})):g("",!0)]),!e(y).suppliers.length&&!e(l)?(o(),$("p",_e,h(r.$t("customers.no_matching_customers")),1)):g("",!0)])])}}},ye={class:"pt-6 mt-5 border-t border-solid lg:pt-8 md:pt-4 border-gray-200"},ge={key:0,class:"text-sm font-bold leading-5 text-black non-italic"},be={key:0},he={key:1},Be={key:1,class:"text-sm font-bold leading-5 text-black non-italic"},ve={setup(F){const y=V(),s=B(()=>y.selectedViewSupplier),p=B(()=>y.isFetchingViewData),c=B(()=>s?.value?.fields?s?.value?.fields:[]);return(l,n)=>{const w=i("BaseHeading"),d=i("BaseDescriptionListItem"),x=i("BaseDescriptionList"),m=i("BaseCustomerAddressDisplay");return o(),$("div",ye,[t(w,null,{default:a(()=>[k(h(l.$t("customers.basic_info")),1)]),_:1}),t(x,null,{default:a(()=>[t(d,{"content-loading":e(p),label:l.$t("customers.display_name"),value:e(s)?.name},null,8,["content-loading","label","value"]),t(d,{"content-loading":e(p),label:l.$t("customers.primary_contact_name"),value:e(s)?.contact_name},null,8,["content-loading","label","value"]),t(d,{"content-loading":e(p),label:l.$t("customers.email"),value:e(s)?.email},null,8,["content-loading","label","value"])]),_:1}),t(x,{class:"mt-5"},{default:a(()=>[t(d,{"content-loading":e(p),label:l.$t("wizard.currency"),value:e(s)?.currency?`${e(s)?.currency?.code} (${e(s)?.currency?.symbol})`:""},null,8,["content-loading","label","value"]),t(d,{"content-loading":e(p),label:l.$t("customers.phone_number"),value:e(s)?.phone},null,8,["content-loading","label","value"]),t(d,{"content-loading":e(p),label:l.$t("customers.website"),value:e(s)?.website},null,8,["content-loading","label","value"]),t(d,{"content-loading":e(p),label:l.$t("customers.prefix"),value:e(s)?.prefix},null,8,["content-loading","label","value"])]),_:1}),e(s).billing||e(s).shipping?(o(),u(w,{key:0,class:"mt-8"},{default:a(()=>[k(h(l.$t("customers.address")),1)]),_:1})):g("",!0),t(x,{class:"mt-5"},{default:a(()=>[e(s).billing?(o(),u(d,{key:0,"content-loading":e(p),label:l.$t("customers.billing_address")},{default:a(()=>[t(m,{address:e(s).billing},null,8,["address"])]),_:1},8,["content-loading","label"])):g("",!0),e(s).shipping?(o(),u(d,{key:1,"content-loading":e(p),label:l.$t("customers.shipping_address")},{default:a(()=>[t(m,{address:e(s).shipping},null,8,["address"])]),_:1},8,["content-loading","label"])):g("",!0)]),_:1}),e(c).length>0?(o(),u(w,{key:1,class:"mt-8"},{default:a(()=>[k(h(l.$t("settings.custom_fields.title")),1)]),_:1})):g("",!0),t(x,{class:"mt-5"},{default:a(()=>[(o(!0),$(U,null,j(e(c),(b,I)=>(o(),u(d,{key:I,"content-loading":e(p),label:b.custom_field.label},{default:a(()=>[b.type==="Switch"?(o(),$("p",ge,[b.default_answer===1?(o(),$("span",be," Yes ")):(o(),$("span",he," No "))])):(o(),$("p",Be,h(b.default_answer),1))]),_:2},1032,["content-loading","label"]))),128))]),_:1})])}}},we={setup(F){K();const y=V();H("utils");const s=L();let p=S(!1),c=A({}),l=A({});A(["This year","Previous year"]);let n=S("This year");B(()=>c.expenseTotals?c.expenseTotals:[]),B(()=>c.netProfits?c.netProfits:[]),B(()=>c&&c.months?c.months:[]),B(()=>c.receiptTotals?c.receiptTotals:[]),B(()=>c.invoiceTotals?c.invoiceTotals:[]),Q(s,()=>{s.params.id&&w(),n.value="This year"},{immediate:!0});async function w(){p.value=!1;let d=await y.fetchViewSupplier({id:s.params.id});d.data&&(Object.assign(c,d.data.meta.chartData),Object.assign(l,d.data.data)),p.value=!0}return(d,x)=>{const m=i("BaseCard");return o(),u(m,{class:"flex flex-col mt-6"},{default:a(()=>[t(ve)]),_:1})}}},ke={setup(F){H("utils"),W();const y=V(),s=Z();O();const p=ee(),c=L();S(null);const l=B(()=>y.selectedViewSupplier.supplier?y.selectedViewSupplier.supplier.name:"");let n=B(()=>y.isFetchingViewData);function w(){return s.hasAbilities([v.CREATE_ESTIMATE,v.CREATE_INVOICE,v.CREATE_PAYMENT,v.CREATE_EXPENSE])}function d(){return s.hasAbilities([v.DELETE_CUSTOMER,v.EDIT_CUSTOMER])}function x(){p.push("/admin/suppliers")}return(m,b)=>{const I=i("BaseButton"),r=i("router-link"),_=i("BaseIcon"),T=i("BaseDropdownItem"),R=i("BaseDropdown"),D=i("BasePageHeader"),C=i("BasePage");return o(),u(C,{class:"xl:pl-96"},{default:a(()=>[t(D,{title:e(l)},{actions:a(()=>[e(s).hasAbilities(e(v).EDIT_CUSTOMER)?(o(),u(r,{key:0,to:`/admin/suppliers/${e(c).params.id}/edit`},{default:a(()=>[t(I,{class:"mr-3",variant:"primary-outline","content-loading":e(n)},{default:a(()=>[k(h(m.$t("general.edit")),1)]),_:1},8,["content-loading"])]),_:1},8,["to"])):g("",!0),w()?te((o(),u(R,{key:1,position:"bottom-end","content-loading":e(n)},{activator:a(()=>[t(I,{class:"mr-3",variant:"primary","content-loading":e(n)},{default:a(()=>[k(h(m.$t("customers.new_transaction")),1)]),_:1},8,["content-loading"])]),default:a(()=>[e(s).hasAbilities(e(v).CREATE_ESTIMATE)?(o(),u(r,{key:0,to:`/admin/estimates/create?supplier=${m.$route.params.id}`},{default:a(()=>[t(T,{class:""},{default:a(()=>[t(_,{name:"DocumentIcon",class:"mr-3 text-gray-600"}),k(" "+h(m.$t("estimates.new_estimate")),1)]),_:1})]),_:1},8,["to"])):g("",!0),e(s).hasAbilities(e(v).CREATE_INVOICE)?(o(),u(r,{key:1,to:`/admin/invoices/create?supplier=${m.$route.params.id}`},{default:a(()=>[t(T,null,{default:a(()=>[t(_,{name:"DocumentTextIcon",class:"mr-3 text-gray-600"}),k(" "+h(m.$t("invoices.new_invoice")),1)]),_:1})]),_:1},8,["to"])):g("",!0),e(s).hasAbilities(e(v).CREATE_PAYMENT)?(o(),u(r,{key:2,to:`/admin/payments/create?supplier=${m.$route.params.id}`},{default:a(()=>[t(T,null,{default:a(()=>[t(_,{name:"CreditCardIcon",class:"mr-3 text-gray-600"}),k(" "+h(m.$t("payments.new_payment")),1)]),_:1})]),_:1},8,["to"])):g("",!0),e(s).hasAbilities(e(v).CREATE_EXPENSE)?(o(),u(r,{key:3,to:`/admin/expenses/create?supplier=${m.$route.params.id}`},{default:a(()=>[t(T,null,{default:a(()=>[t(_,{name:"CalculatorIcon",class:"mr-3 text-gray-600"}),k(" "+h(m.$t("expenses.new_expense")),1)]),_:1})]),_:1},8,["to"])):g("",!0)]),_:1},8,["content-loading"])),[[ae,!1]]):g("",!0),d()?(o(),u(ne,{key:2,class:z({"ml-3":e(n)}),row:e(y).selectedViewSupplier,"load-data":x},null,8,["class","row"])):g("",!0)]),_:1},8,["title"]),t(fe),t(we)]),_:1})}}};export{ke as default};
