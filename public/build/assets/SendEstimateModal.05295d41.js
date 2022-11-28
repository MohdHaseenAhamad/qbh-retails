import{Q as O,V as T,u as H,H as Q,aI as X,e as J,g as h,y as K,l as C,h as p,i as B,j as E,k as W,r as d,o as _,n as M,w as l,b as v,q as f,t as $,m as e,a,c as N,U}from"./main.d0ffa6eb.js";const Y={class:"flex justify-between w-full"},Z={key:0,action:""},ee={class:"px-8 py-8 sm:p-6"},te={class:"z-0 flex justify-end p-4 border-t border-gray-200 border-solid"},ae={key:1},oe={class:"my-6 mx-4 border border-gray-200 relative"},se=f(" Edit "),re=["src"],le={class:"z-0 flex justify-end p-4 border-t border-gray-200 border-solid"},ue={setup(ne){const m=O(),j=T(),x=H(),k=Q();X();const{t:u}=J(),n=h(!1),I=h(""),b=h(!1),P=h(["customer","customerCustom","estimate","estimateCustom","company"]);let o=K({id:null,from:null,to:null,subject:"New Estimate",body:null});const D=C(()=>m.active&&m.componentName==="SendEstimateModal"),q=C(()=>m.data),G={from:{required:p.withMessage(u("validation.required"),B),email:p.withMessage(u("validation.email_incorrect"),E)},to:{required:p.withMessage(u("validation.required"),B),email:p.withMessage(u("validation.email_incorrect"),E)},subject:{required:p.withMessage(u("validation.required"),B)},body:{required:p.withMessage(u("validation.required"),B)}},s=W(G,C(()=>o));function F(){b.value=!1}async function L(){let r=await k.fetchBasicMailConfig();o.id=m.id,r.data&&(o.from=r.data.from_mail),q.value&&(o.to=q.value.customer.email),o.body=k.selectedCompanySettings.estimate_mail_body}async function S(){if(s.value.$touch(),s.value.$invalid)return!0;try{if(n.value=!0,!b.value){const c=await j.previewEstimate(o);n.value=!1,b.value=!0;var r=new Blob([c.data],{type:"text/html"});I.value=URL.createObjectURL(r);return}const t=await j.sendEstimate(o);if(n.value=!1,t.data.success)return y(),!0}catch(t){console.error(t),n.value=!1,x.showNotification({type:"error",message:u("estimates.something_went_wrong")})}}function y(){m.closeModal(),setTimeout(()=>{s.value.$reset(),b.value=!1,I.value=null},300)}return(r,t)=>{const c=d("BaseIcon"),V=d("BaseInput"),w=d("BaseInputGroup"),R=d("BaseCustomInput"),z=d("BaseInputGrid"),g=d("BaseButton"),A=d("BaseModal");return _(),M(A,{show:e(D),onClose:y,onOpen:L},{header:l(()=>[v("div",Y,[f($(e(m).title)+" ",1),a(c,{name:"XIcon",class:"h-6 w-6 text-gray-500 cursor-pointer",onClick:y})])]),default:l(()=>[b.value?(_(),N("div",ae,[v("div",oe,[a(g,{class:"absolute top-4 right-4",disabled:n.value,variant:"primary-outline",onClick:F},{default:l(()=>[a(c,{name:"PencilIcon",class:"h-5 mr-2"}),se]),_:1},8,["disabled"]),v("iframe",{src:I.value,frameborder:"0",class:"w-full",style:{"min-height":"500px"}},null,8,re)]),v("div",le,[a(g,{class:"mr-3",variant:"primary-outline",type:"button",onClick:y},{default:l(()=>[f($(r.$t("general.cancel")),1)]),_:1}),a(g,{loading:n.value,disabled:n.value,variant:"primary",type:"button",onClick:S},{default:l(()=>[n.value?U("",!0):(_(),M(c,{key:0,name:"PaperAirplaneIcon",class:"mr-2"})),f(" "+$(r.$t("general.send")),1)]),_:1},8,["loading","disabled"])])])):(_(),N("form",Z,[v("div",ee,[a(z,{layout:"one-column"},{default:l(()=>[a(w,{label:r.$t("general.from"),required:"",error:e(s).from.$error&&e(s).from.$errors[0].$message},{default:l(()=>[a(V,{modelValue:e(o).from,"onUpdate:modelValue":t[0]||(t[0]=i=>e(o).from=i),type:"text",invalid:e(s).from.$error,onInput:t[1]||(t[1]=i=>e(s).from.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"]),a(w,{label:r.$t("general.to"),required:"",error:e(s).to.$error&&e(s).to.$errors[0].$message},{default:l(()=>[a(V,{modelValue:e(o).to,"onUpdate:modelValue":t[2]||(t[2]=i=>e(o).to=i),type:"text",invalid:e(s).to.$error,onInput:t[3]||(t[3]=i=>e(s).to.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"]),a(w,{label:r.$t("general.subject"),required:"",error:e(s).subject.$error&&e(s).subject.$errors[0].$message},{default:l(()=>[a(V,{modelValue:e(o).subject,"onUpdate:modelValue":t[4]||(t[4]=i=>e(o).subject=i),type:"text",invalid:e(s).subject.$error,onInput:t[5]||(t[5]=i=>e(s).subject.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"]),a(w,{label:r.$t("general.body"),required:""},{default:l(()=>[a(R,{modelValue:e(o).body,"onUpdate:modelValue":t[6]||(t[6]=i=>e(o).body=i),fields:P.value},null,8,["modelValue","fields"])]),_:1},8,["label"])]),_:1})]),v("div",te,[a(g,{class:"mr-3",variant:"primary-outline",type:"button",onClick:y},{default:l(()=>[f($(r.$t("general.cancel")),1)]),_:1}),a(g,{loading:n.value,disabled:n.value,variant:"primary",type:"button",class:"mr-3",onClick:S},{default:l(()=>[n.value?U("",!0):(_(),M(c,{key:0,name:"PhotographIcon",class:"h-5 mr-2"})),f(" "+$(r.$t("general.preview")),1)]),_:1},8,["loading","disabled"])])]))]),_:1},8,["show"])}}};export{ue as _};
