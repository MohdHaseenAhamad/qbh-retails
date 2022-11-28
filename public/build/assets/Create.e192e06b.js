import{x as ue,f as me,am as ce,u as de,X as ye,a1 as pe,H as _e,Q as ve,P as fe,a2 as Pe,F as ge,e as be,g as q,y as $e,l as B,h,i as S,a5 as Be,aP as he,k as Se,O as Ve,aK as Ie,r as u,o as V,c as Ce,a as r,w as l,b as $,m as e,L as qe,n as w,E as R,U as x,q as M,t as v,p as L,s as we,aa as Me}from"./main.d0ffa6eb.js";import{_ as ke}from"./ExchangeRateConverter.ef383681.js";import{_ as Ee}from"./SelectNotePopup.f61bb6cc.js";import{_ as Ue}from"./CreateCustomFields.e1e8603d.js";import{_ as Ne}from"./PaymentModeModal.72b41f55.js";import"./NoteModal.688693f3.js";/* empty css                                                  *//* empty css                                                                      */const De=["onSubmit"],Ae={class:"absolute left-3.5"},Fe={class:"relative w-full"},Ge={class:"relative w-full"},Re={class:"relative mt-6"},xe={class:"z-20 float-right text-sm font-semibold leading-5 text-primary-400"},Le={class:"mb-4 text-sm font-medium text-primary-800"},We={setup(He){const d=ue(),H=me(),t=ce();de();const T=ye();pe(),_e();const j=ve(),E=fe();Pe();const U=ge("utils"),{t:y}=be();let f=q(!1),I=q(!1),p=q([]);const P=q(null),N="newEstimate",z=$e(["customer","company","customerCustom","payment","paymentCustom"]),C=B({get:()=>t.currentPayment.amount/100,set:n=>{t.currentPayment.amount=Math.round(n*100)}}),k=B({get:()=>t.currentPayment.discount/100,set:n=>{t.currentPayment.discount=Math.round(n*100)}}),s=B(()=>t.isFetchingInitialData),m=B(()=>d.name==="payments.edit"),D=B(()=>m.value?y("payments.edit_payment"):y("payments.new_payment")),O=B(()=>(console.log(t.currentPayment,"invoiceStore.totalinvoiceStore.total  "),{currentPayment:{customer_id:{required:h.withMessage(y("validation.required"),S)},payment_date:{required:h.withMessage(y("validation.required"),S)},amount:{required:h.withMessage(y("validation.required"),S)},invoice_id:{required:h.withMessage(y("validation.required"),S)},exchange_rate:{required:Be(function(){return h.withMessage(y("validation.required"),S),t.showExchangeRate}),decimal:h.withMessage(y("validation.valid_exchange_rate"),he)}}})),i=Se(O,t,{$scope:N});Ve(()=>{t.currentPayment.customer_id&&W(t.currentPayment.customer_id),d.query.customer&&(t.currentPayment.customer_id=d.query.customer)}),t.resetCurrentPayment(),d.query.customer&&(t.currentPayment.customer_id=d.query.customer),t.fetchPaymentInitialData(m.value),d.params.id&&!m.value&&X();async function K(){j.openModal({title:y("settings.payment_modes.add_payment_mode"),componentName:"PaymentModeModal"})}function Q(n){t.currentPayment.notes=""+n.notes}async function X(){let n=await E.fetchInvoice(d?.params?.id);t.currentPayment.customer_id=n.data.data.customer.id,t.currentPayment.invoice_id=n.data.data.id}async function J(n){n&&(P.value=p.value.find(a=>a.id===n),C.value=P.value.due_amount/100,t.currentPayment.maxPayableAmount=P.value.due_amount)}function W(n){if(n){let a={customer_id:n,doublestatus:"SENTOVERDUE",limit:"all"};console.log(m.value,"isEdit.valueisEdit.valueisEdit.value"),m.value&&(a.ownid=t.currentPayment.invoice_id),I.value=!0,Promise.all([E.fetchInvoices(a),T.fetchCustomer(n)]).then(async([c,g])=>{c&&(p.value=[...c.data.data]),g&&g.data&&(t.currentPayment.selectedCustomer=g.data.data,t.currentPayment.customer=g.data.data,t.currentPayment.currency=g.data.data.currency),m.value&&t.currentPayment.invoice_id&&(P.value=p.value.find(_=>_.id===t.currentPayment.invoice_id),t.currentPayment.maxPayableAmount=P.value.due_amount+t.currentPayment.amount),m.value&&(p.value=p.value.filter(_=>_.due_amount>0||_.id==t.currentPayment.invoice_id)),I.value=!1}).catch(c=>{I.value=!1,console.error(c,"error")})}}Ie(()=>{t.resetCurrentPayment(),p.value=[]});async function Y(){if(i.value.$touch(),i.value.$invalid)return!1;f.value=!0;let n={...t.currentPayment},a=null;try{a=await(m.value?t.updatePayment:t.addPayment)(n),H.push(`/admin/payments/${a.data.data.id}/view`)}catch{f.value=!1}}function Z(n){let a={userId:n};d.params.id&&(a.model_id=d.params.id),t.currentPayment.invoice_id=P.value=null,t.currentPayment.amount=100,p.value=[],t.getNextNumber(a,!0)}return(n,a)=>{const c=u("BaseBreadcrumbItem"),g=u("BaseBreadcrumb"),_=u("BaseIcon"),A=u("BaseButton"),ee=u("BasePageHeader"),te=u("BaseDatePicker"),b=u("BaseInputGroup"),ne=u("BaseInput"),ae=u("BaseCustomerSelectInput"),F=u("BaseMultiselect"),G=u("BaseMoney"),oe=u("BaseSelectAction"),re=u("BaseInputGrid"),le=u("BaseCustomInput"),se=u("BaseCard"),ie=u("BasePage");return V(),Ce(Me,null,[r(Ne),r(ie,{class:"relative payment-create"},{default:l(()=>[$("form",{action:"",onSubmit:we(Y,["prevent"])},[r(ee,{title:e(D),class:"mb-5"},qe({default:l(()=>[r(g,null,{default:l(()=>[r(c,{title:n.$t("general.home"),to:"/admin/dashboard"},null,8,["title"]),r(c,{title:n.$tc("payments.payment",2),to:"/admin/payments"},null,8,["title"]),r(c,{title:e(D),to:"#",active:""},null,8,["title"])]),_:1})]),_:2},[(e(t).currentPayment.invoice?e(t).currentPayment.invoice.due_amount!=0:!0)?{name:"actions",fn:l(()=>[r(A,{loading:e(f),disabled:e(f),variant:"primary",type:"submit",class:"hidden sm:flex"},{left:l(o=>[e(f)?x("",!0):(V(),w(_,{key:0,name:"SaveIcon",class:R(o.class)},null,8,["class"]))]),default:l(()=>[M(" "+v(e(m)?n.$t("payments.update_payment"):n.$t("payments.save_payment")),1)]),_:1},8,["loading","disabled"])])}:void 0]),1032,["title"]),r(se,null,{default:l(()=>[r(re,null,{default:l(()=>[r(b,{label:n.$t("payments.date"),"content-loading":e(s),required:"",error:e(i).currentPayment.payment_date.$error&&e(i).currentPayment.payment_date.$errors[0].$message},{default:l(()=>[r(te,{modelValue:e(t).currentPayment.payment_date,"onUpdate:modelValue":[a[0]||(a[0]=o=>e(t).currentPayment.payment_date=o),a[1]||(a[1]=o=>e(i).currentPayment.payment_date.$touch())],"content-loading":e(s),"calendar-button":!0,"calendar-button-icon":"calendar",invalid:e(i).currentPayment.payment_date.$error},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),r(b,{label:n.$t("payments.payment_number"),"content-loading":e(s),required:""},{default:l(()=>[r(ne,{modelValue:e(t).currentPayment.payment_number,"onUpdate:modelValue":a[2]||(a[2]=o=>e(t).currentPayment.payment_number=o),"content-loading":e(s)},null,8,["modelValue","content-loading"])]),_:1},8,["label","content-loading"]),r(b,{label:n.$t("payments.customer"),error:e(i).currentPayment.customer_id.$error&&e(i).currentPayment.customer_id.$errors[0].$message,"content-loading":e(s),required:""},{default:l(()=>[r(ae,{modelValue:e(t).currentPayment.customer_id,"onUpdate:modelValue":[a[3]||(a[3]=o=>e(t).currentPayment.customer_id=o),a[4]||(a[4]=o=>Z(e(t).currentPayment.customer_id))],"content-loading":e(s),invalid:e(i).currentPayment.customer_id.$error,placeholder:n.$t("customers.select_a_customer"),"fetch-all":e(m),"show-action":""},null,8,["modelValue","content-loading","invalid","placeholder","fetch-all"])]),_:1},8,["label","error","content-loading"]),r(b,{"content-loading":e(s),label:n.$t("payments.invoice"),"help-text":P.value?`Due Amount: ${(e(t).currentPayment.invoice?e(t).currentPayment.invoice.due_amount:e(t).currentPayment.maxPayableAmount)/100}`:"",required:"",error:e(i).currentPayment.invoice_id.$error&&e(i).currentPayment.invoice_id.$errors[0].$message},{default:l(()=>[r(F,{modelValue:e(t).currentPayment.invoice_id,"onUpdate:modelValue":a[5]||(a[5]=o=>e(t).currentPayment.invoice_id=o),"content-loading":e(s),"value-prop":"id","track-by":"invoice_number",label:"invoice_number",options:e(p),loading:e(I),placeholder:n.$t("invoices.select_invoice"),onSelect:J},{singlelabel:l(({value:o})=>[$("div",Ae,v(o.invoice_number)+" ("+v(e(U).formatMoney(o.total,o.customer.currency))+") ",1)]),option:l(({option:o})=>[M(v(o.invoice_number)+" ("+v(e(U).formatMoney(o.total,o.customer.currency))+") ",1)]),_:1},8,["modelValue","content-loading","options","loading","placeholder"])]),_:1},8,["content-loading","label","help-text","error"]),r(b,{label:n.$t("payments.recieved_amount"),"content-loading":e(s),error:e(i).currentPayment.amount.$error&&e(i).currentPayment.amount.$errors[0].$message,required:""},{default:l(()=>[$("div",Fe,[(V(),w(G,{key:e(t).currentPayment.currency,modelValue:e(C),"onUpdate:modelValue":[a[6]||(a[6]=o=>L(C)?C.value=o:null),a[7]||(a[7]=o=>e(i).currentPayment.amount.$touch())],currency:e(t).currentPayment.currency,"content-loading":e(s),invalid:e(i).currentPayment.amount.$error},null,8,["modelValue","currency","content-loading","invalid"]))])]),_:1},8,["label","content-loading","error"]),r(b,{label:n.$t("payments.discount"),"content-loading":e(s)},{default:l(()=>[$("div",Ge,[(V(),w(G,{key:e(t).currentPayment.currency,modelValue:e(k),"onUpdate:modelValue":a[8]||(a[8]=o=>L(k)?k.value=o:null),currency:e(t).currentPayment.currency,"content-loading":e(s)},null,8,["modelValue","currency","content-loading"]))])]),_:1},8,["label","content-loading"]),r(b,{"content-loading":e(s),label:n.$t("payments.payment_mode")},{default:l(()=>[r(F,{modelValue:e(t).currentPayment.payment_method_id,"onUpdate:modelValue":a[9]||(a[9]=o=>e(t).currentPayment.payment_method_id=o),"content-loading":e(s),label:"name","value-prop":"id","track-by":"name",options:e(t).paymentModes,placeholder:n.$t("payments.select_payment_mode"),searchable:""},{action:l(()=>[r(oe,{onClick:K},{default:l(()=>[r(_,{name:"PlusIcon",class:"h-4 mr-2 -ml-2 text-center text-primary-400"}),M(" "+v(n.$t("settings.payment_modes.add_payment_mode")),1)]),_:1})]),_:1},8,["modelValue","content-loading","options","placeholder"])]),_:1},8,["content-loading","label"]),r(ke,{store:e(t),"store-prop":"currentPayment",v:e(i).currentPayment,"is-loading":e(s),"is-edit":e(m),"customer-currency":e(t).currentPayment.currency_id},null,8,["store","v","is-loading","is-edit","customer-currency"])]),_:1}),r(Ue,{type:"Payment","is-edit":e(m),"is-loading":e(s),store:e(t),"store-prop":"currentPayment","custom-field-scope":N,class:"mt-6"},null,8,["is-edit","is-loading","store"]),$("div",Re,[$("div",xe,[r(Ee,{type:"Payment",onSelect:Q})]),$("label",Le,v(n.$t("estimates.notes")),1),r(le,{modelValue:e(t).currentPayment.notes,"onUpdate:modelValue":a[10]||(a[10]=o=>e(t).currentPayment.notes=o),"content-loading":e(s),fields:e(z),class:"mt-1"},null,8,["modelValue","content-loading","fields"])]),r(A,{loading:e(f),"content-loading":e(s),variant:"primary",type:"submit",class:"flex justify-center w-full mt-4 sm:hidden md:hidden"},{left:l(o=>[e(f)?x("",!0):(V(),w(_,{key:0,name:"SaveIcon",class:R(o.class)},null,8,["class"]))]),default:l(()=>[M(" "+v(e(m)?n.$t("payments.update_payment"):n.$t("payments.save_payment")),1)]),_:1},8,["loading","content-loading"])]),_:1})],40,De)]),_:1})],64)}}};export{We as default};