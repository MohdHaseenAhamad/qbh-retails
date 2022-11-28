import{aI as E,e as T,g as j,y as R,l as h,h as f,i as C,ak as K,j as x,k as P,M as L,r as c,o as q,c as G,a as l,w as s,m as e,n as B,p as O,b as F,E as z,U as A,q as S,t as k,C as Q,s as N,Q as W,a4 as X,D as Y,aa as Z}from"./main.d0ffa6eb.js";const ee=["onSubmit"],ie={class:"flex my-10"},H={props:{configData:{type:Object,require:!0,default:Object},isSaving:{type:Boolean,require:!0,default:!1},isFetchingInitialData:{type:Boolean,require:!0,default:!1},mailDrivers:{type:Array,require:!0,default:Array}},emits:["submit-data","on-change-driver"],setup(a,{emit:D}){const $=a,i=E(),{t:u}=T();let m=j(!1);const y=R(["tls","ssl","starttls"]),V=h(()=>m.value?"text":"password"),t=h(()=>({smtpConfig:{mail_driver:{required:f.withMessage(u("validation.required"),C)},mail_host:{required:f.withMessage(u("validation.required"),C)},mail_port:{required:f.withMessage(u("validation.required"),C),numeric:f.withMessage(u("validation.numbers_only"),K)},mail_encryption:{required:f.withMessage(u("validation.required"),C)},from_mail:{required:f.withMessage(u("validation.required"),C),email:f.withMessage(u("validation.email_incorrect"),x)},from_name:{required:f.withMessage(u("validation.required"),C)}}})),d=P(t,h(()=>i));L(()=>{for(const o in i.smtpConfig)$.configData.hasOwnProperty(o)&&(i.smtpConfig[o]=$.configData[o])});async function I(){return d.value.smtpConfig.$touch(),d.value.smtpConfig.$invalid||D("submit-data",i.smtpConfig),!1}function g(){d.value.smtpConfig.mail_driver.$touch(),D("on-change-driver",i.smtpConfig.mail_driver)}return(o,n)=>{const M=c("BaseMultiselect"),v=c("BaseInputGroup"),b=c("BaseInput"),_=c("BaseIcon"),U=c("BaseInputGrid"),p=c("BaseButton");return q(),G("form",{onSubmit:N(I,["prevent"])},[l(U,null,{default:s(()=>[l(v,{label:o.$t("settings.mail.driver"),"content-loading":a.isFetchingInitialData,error:e(d).smtpConfig.mail_driver.$error&&e(d).smtpConfig.mail_driver.$errors[0].$message,required:""},{default:s(()=>[l(M,{modelValue:e(i).smtpConfig.mail_driver,"onUpdate:modelValue":[n[0]||(n[0]=r=>e(i).smtpConfig.mail_driver=r),g],"content-loading":a.isFetchingInitialData,options:a.mailDrivers,"can-deselect":!1,invalid:e(d).smtpConfig.mail_driver.$error},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.host"),"content-loading":a.isFetchingInitialData,error:e(d).smtpConfig.mail_host.$error&&e(d).smtpConfig.mail_host.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).smtpConfig.mail_host,"onUpdate:modelValue":n[1]||(n[1]=r=>e(i).smtpConfig.mail_host=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mail_host",invalid:e(d).smtpConfig.mail_host.$error,onInput:n[2]||(n[2]=r=>e(d).smtpConfig.mail_host.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{"content-loading":a.isFetchingInitialData,label:o.$t("settings.mail.username")},{default:s(()=>[l(b,{modelValue:e(i).smtpConfig.mail_username,"onUpdate:modelValue":n[3]||(n[3]=r=>e(i).smtpConfig.mail_username=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"db_name"},null,8,["modelValue","content-loading"])]),_:1},8,["content-loading","label"]),l(v,{"content-loading":a.isFetchingInitialData,label:o.$t("settings.mail.password")},{default:s(()=>[l(b,{modelValue:e(i).smtpConfig.mail_password,"onUpdate:modelValue":n[6]||(n[6]=r=>e(i).smtpConfig.mail_password=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:e(V),name:"password"},{right:s(()=>[e(m)?(q(),B(_,{key:0,class:"mr-1 text-gray-500 cursor-pointer",name:"EyeOffIcon",onClick:n[4]||(n[4]=r=>O(m)?m.value=!e(m):m=!e(m))})):(q(),B(_,{key:1,class:"mr-1 text-gray-500 cursor-pointer",name:"EyeIcon",onClick:n[5]||(n[5]=r=>O(m)?m.value=!e(m):m=!e(m))}))]),_:1},8,["modelValue","content-loading","type"])]),_:1},8,["content-loading","label"]),l(v,{label:o.$t("settings.mail.port"),"content-loading":a.isFetchingInitialData,error:e(d).smtpConfig.mail_port.$error&&e(d).smtpConfig.mail_port.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).smtpConfig.mail_port,"onUpdate:modelValue":n[7]||(n[7]=r=>e(i).smtpConfig.mail_port=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mail_port",invalid:e(d).smtpConfig.mail_port.$error,onInput:n[8]||(n[8]=r=>e(d).smtpConfig.mail_port.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.encryption"),"content-loading":a.isFetchingInitialData,error:e(d).smtpConfig.mail_encryption.$error&&e(d).smtpConfig.mail_encryption.$errors[0].$message,required:""},{default:s(()=>[l(M,{modelValue:e(i).smtpConfig.mail_encryption,"onUpdate:modelValue":n[9]||(n[9]=r=>e(i).smtpConfig.mail_encryption=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,options:e(y),searchable:!0,"show-labels":!1,placeholder:"Select option",invalid:e(d).smtpConfig.mail_encryption.$error,onInput:n[10]||(n[10]=r=>e(d).smtpConfig.mail_encryption.$touch())},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.from_mail"),"content-loading":a.isFetchingInitialData,error:e(d).smtpConfig.from_mail.$error&&e(d).smtpConfig.from_mail.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).smtpConfig.from_mail,"onUpdate:modelValue":n[11]||(n[11]=r=>e(i).smtpConfig.from_mail=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"from_mail",invalid:e(d).smtpConfig.from_mail.$error,onInput:n[12]||(n[12]=r=>e(d).smtpConfig.from_mail.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.from_name"),"content-loading":a.isFetchingInitialData,error:e(d).smtpConfig.from_name.$error&&e(d).smtpConfig.from_name.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).smtpConfig.from_name,"onUpdate:modelValue":n[13]||(n[13]=r=>e(i).smtpConfig.from_name=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"from_name",invalid:e(d).smtpConfig.from_name.$error,onInput:n[14]||(n[14]=r=>e(d).smtpConfig.from_name.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"])]),_:1}),F("div",ie,[l(p,{disabled:a.isSaving,"content-loading":a.isFetchingInitialData,loading:a.isSaving,type:"submit",variant:"primary"},{left:s(r=>[a.isSaving?A("",!0):(q(),B(_,{key:0,name:"SaveIcon",class:z(r.class)},null,8,["class"]))]),default:s(()=>[S(" "+k(o.$t("general.save")),1)]),_:1},8,["disabled","content-loading","loading"]),Q(o.$slots,"default")])],40,ee)}}},ne=["onSubmit"],te={class:"flex my-10"},ae={props:{configData:{type:Object,require:!0,default:Object},isSaving:{type:Boolean,require:!0,default:!1},isFetchingInitialData:{type:Boolean,require:!0,default:!1},mailDrivers:{type:Array,require:!0,default:Array}},emits:["submit-data","on-change-driver"],setup(a,{emit:D}){const $=a,i=E(),{t:u}=T();let m=j(!1);const y=h(()=>m.value?"text":"password"),V=h(()=>({mailgunConfig:{mail_driver:{required:f.withMessage(u("validation.required"),C)},mail_mailgun_domain:{required:f.withMessage(u("validation.required"),C)},mail_mailgun_endpoint:{required:f.withMessage(u("validation.required"),C)},mail_mailgun_secret:{required:f.withMessage(u("validation.required"),C)},from_mail:{required:f.withMessage(u("validation.required"),C),email:x},from_name:{required:f.withMessage(u("validation.required"),C)}}})),t=P(V,h(()=>i));L(()=>{for(const g in i.mailgunConfig)$.configData.hasOwnProperty(g)&&(i.mailgunConfig[g]=$.configData[g])});async function d(){return t.value.mailgunConfig.$touch(),t.value.mailgunConfig.$invalid||D("submit-data",i.mailgunConfig),!1}function I(){t.value.mailgunConfig.mail_driver.$touch(),D("on-change-driver",i.mailgunConfig.mail_driver)}return(g,o)=>{const n=c("BaseMultiselect"),M=c("BaseInputGroup"),v=c("BaseInput"),b=c("BaseIcon"),_=c("BaseInputGrid"),U=c("BaseButton");return q(),G("form",{onSubmit:N(d,["prevent"])},[l(_,null,{default:s(()=>[l(M,{label:g.$t("settings.mail.driver"),"content-loading":a.isFetchingInitialData,error:e(t).mailgunConfig.mail_driver.$error&&e(t).mailgunConfig.mail_driver.$errors[0].$message,required:""},{default:s(()=>[l(n,{modelValue:e(i).mailgunConfig.mail_driver,"onUpdate:modelValue":[o[0]||(o[0]=p=>e(i).mailgunConfig.mail_driver=p),I],"content-loading":a.isFetchingInitialData,options:a.mailDrivers,"can-deselect":!1,invalid:e(t).mailgunConfig.mail_driver.$error},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),l(M,{label:g.$t("settings.mail.mailgun_domain"),"content-loading":a.isFetchingInitialData,error:e(t).mailgunConfig.mail_mailgun_domain.$error&&e(t).mailgunConfig.mail_mailgun_domain.$errors[0].$message,required:""},{default:s(()=>[l(v,{modelValue:e(i).mailgunConfig.mail_mailgun_domain,"onUpdate:modelValue":o[1]||(o[1]=p=>e(i).mailgunConfig.mail_mailgun_domain=p),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mailgun_domain",invalid:e(t).mailgunConfig.mail_mailgun_domain.$error,onInput:o[2]||(o[2]=p=>e(t).mailgunConfig.mail_mailgun_domain.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(M,{label:g.$t("settings.mail.mailgun_secret"),"content-loading":a.isFetchingInitialData,error:e(t).mailgunConfig.mail_mailgun_secret.$error&&e(t).mailgunConfig.mail_mailgun_secret.$errors[0].$message,required:""},{default:s(()=>[l(v,{modelValue:e(i).mailgunConfig.mail_mailgun_secret,"onUpdate:modelValue":o[5]||(o[5]=p=>e(i).mailgunConfig.mail_mailgun_secret=p),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:e(y),name:"mailgun_secret",autocomplete:"off",invalid:e(t).mailgunConfig.mail_mailgun_secret.$error,onInput:o[6]||(o[6]=p=>e(t).mailgunConfig.mail_mailgun_secret.$touch())},{right:s(()=>[e(m)?(q(),B(b,{key:0,class:"mr-1 text-gray-500 cursor-pointer",name:"EyeOffIcon",onClick:o[3]||(o[3]=p=>O(m)?m.value=!e(m):m=!e(m))})):(q(),B(b,{key:1,class:"mr-1 text-gray-500 cursor-pointer",name:"EyeIcon",onClick:o[4]||(o[4]=p=>O(m)?m.value=!e(m):m=!e(m))}))]),_:1},8,["modelValue","content-loading","type","invalid"])]),_:1},8,["label","content-loading","error"]),l(M,{label:g.$t("settings.mail.mailgun_endpoint"),"content-loading":a.isFetchingInitialData,error:e(t).mailgunConfig.mail_mailgun_endpoint.$error&&e(t).mailgunConfig.mail_mailgun_endpoint.$errors[0].$message,required:""},{default:s(()=>[l(v,{modelValue:e(i).mailgunConfig.mail_mailgun_endpoint,"onUpdate:modelValue":o[7]||(o[7]=p=>e(i).mailgunConfig.mail_mailgun_endpoint=p),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mailgun_endpoint",invalid:e(t).mailgunConfig.mail_mailgun_endpoint.$error,onInput:o[8]||(o[8]=p=>e(t).mailgunConfig.mail_mailgun_endpoint.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(M,{label:g.$t("settings.mail.from_mail"),"content-loading":a.isFetchingInitialData,error:e(t).mailgunConfig.from_mail.$error&&e(t).mailgunConfig.from_mail.$errors[0].$message,required:""},{default:s(()=>[l(v,{modelValue:e(i).mailgunConfig.from_mail,"onUpdate:modelValue":o[9]||(o[9]=p=>e(i).mailgunConfig.from_mail=p),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"from_mail",invalid:e(t).mailgunConfig.from_mail.$error,onInput:o[10]||(o[10]=p=>e(t).mailgunConfig.from_mail.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(M,{label:g.$t("settings.mail.from_name"),"content-loading":a.isFetchingInitialData,error:e(t).mailgunConfig.from_name.$error&&e(t).mailgunConfig.from_name.$errors[0].$message,required:""},{default:s(()=>[l(v,{modelValue:e(i).mailgunConfig.from_name,"onUpdate:modelValue":o[11]||(o[11]=p=>e(i).mailgunConfig.from_name=p),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"from_name",invalid:e(t).mailgunConfig.from_name.$error,onInput:o[12]||(o[12]=p=>e(t).mailgunConfig.from_name.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"])]),_:1}),F("div",te,[l(U,{disabled:a.isSaving,"content-loading":a.isFetchingInitialData,loading:a.isSaving,variant:"primary",type:"submit"},{left:s(p=>[a.isSaving?A("",!0):(q(),B(b,{key:0,name:"SaveIcon",class:z(p.class)},null,8,["class"]))]),default:s(()=>[S(" "+k(g.$t("general.save")),1)]),_:1},8,["disabled","content-loading","loading"]),Q(g.$slots,"default")])],40,ne)}}},oe=["onSubmit"],le={class:"flex my-10"},re={props:{configData:{type:Object,require:!0,default:Object},isSaving:{type:Boolean,require:!0,default:!1},isFetchingInitialData:{type:Boolean,require:!0,default:!1},mailDrivers:{type:Array,require:!0,default:Array}},emits:["submit-data","on-change-driver"],setup(a,{emit:D}){const $=a,i=E(),{t:u}=T();let m=j(!1);const y=R(["tls","ssl","starttls"]),V=h(()=>({sesConfig:{mail_driver:{required:f.withMessage(u("validation.required"),C)},mail_host:{required:f.withMessage(u("validation.required"),C)},mail_port:{required:f.withMessage(u("validation.required"),C),numeric:K},mail_ses_key:{required:f.withMessage(u("validation.required"),C)},mail_ses_secret:{required:f.withMessage(u("validation.required"),C)},mail_encryption:{required:f.withMessage(u("validation.required"),C)},from_mail:{required:f.withMessage(u("validation.required"),C),email:f.withMessage(u("validation.email_incorrect"),x)},from_name:{required:f.withMessage(u("validation.required"),C)}}})),t=P(V,h(()=>i)),d=h(()=>m.value?"text":"password");L(()=>{for(const o in i.sesConfig)$.configData.hasOwnProperty(o)&&(i.sesConfig[o]=$.configData[o])});async function I(){return t.value.sesConfig.$touch(),t.value.sesConfig.$invalid||D("submit-data",i.sesConfig),!1}function g(){t.value.sesConfig.mail_driver.$touch(),D("on-change-driver",i.sesConfig.mail_driver)}return(o,n)=>{const M=c("BaseMultiselect"),v=c("BaseInputGroup"),b=c("BaseInput"),_=c("BaseIcon"),U=c("BaseInputGrid"),p=c("BaseButton");return q(),G("form",{onSubmit:N(I,["prevent"])},[l(U,null,{default:s(()=>[l(v,{label:o.$t("settings.mail.driver"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.mail_driver.$error&&e(t).sesConfig.mail_driver.$errors[0].$message,required:""},{default:s(()=>[l(M,{modelValue:e(i).sesConfig.mail_driver,"onUpdate:modelValue":[n[0]||(n[0]=r=>e(i).sesConfig.mail_driver=r),g],"content-loading":a.isFetchingInitialData,options:a.mailDrivers,"can-deselect":!1,invalid:e(t).sesConfig.mail_driver.$error},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.host"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.mail_host.$error&&e(t).sesConfig.mail_host.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).sesConfig.mail_host,"onUpdate:modelValue":n[1]||(n[1]=r=>e(i).sesConfig.mail_host=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mail_host",invalid:e(t).sesConfig.mail_host.$error,onInput:n[2]||(n[2]=r=>e(t).sesConfig.mail_host.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.port"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.mail_port.$error&&e(t).sesConfig.mail_port.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).sesConfig.mail_port,"onUpdate:modelValue":n[3]||(n[3]=r=>e(i).sesConfig.mail_port=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mail_port",invalid:e(t).sesConfig.mail_port.$error,onInput:n[4]||(n[4]=r=>e(t).sesConfig.mail_port.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.encryption"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.mail_encryption.$error&&e(t).sesConfig.mail_encryption.$errors[0].$message,required:""},{default:s(()=>[l(M,{modelValue:e(i).sesConfig.mail_encryption,"onUpdate:modelValue":n[5]||(n[5]=r=>e(i).sesConfig.mail_encryption=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,options:e(y),invalid:e(t).sesConfig.mail_encryption.$error,placeholder:"Select option",onInput:n[6]||(n[6]=r=>e(t).sesConfig.mail_encryption.$touch())},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.from_mail"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.from_mail.$error&&e(t).sesConfig.from_mail.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).sesConfig.from_mail,"onUpdate:modelValue":n[7]||(n[7]=r=>e(i).sesConfig.from_mail=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"from_mail",invalid:e(t).sesConfig.from_mail.$error,onInput:n[8]||(n[8]=r=>e(t).sesConfig.from_mail.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.from_name"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.from_name.$error&&e(t).sesConfig.from_name.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).sesConfig.from_name,"onUpdate:modelValue":n[9]||(n[9]=r=>e(i).sesConfig.from_name=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"name",invalid:e(t).sesConfig.from_name.$error,onInput:n[10]||(n[10]=r=>e(t).sesConfig.from_name.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.ses_key"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.mail_ses_key.$error&&e(t).sesConfig.mail_ses_key.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).sesConfig.mail_ses_key,"onUpdate:modelValue":n[11]||(n[11]=r=>e(i).sesConfig.mail_ses_key=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"mail_ses_key",invalid:e(t).sesConfig.mail_ses_key.$error,onInput:n[12]||(n[12]=r=>e(t).sesConfig.mail_ses_key.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(v,{label:o.$t("settings.mail.ses_secret"),"content-loading":a.isFetchingInitialData,error:e(t).sesConfig.mail_ses_secret.$error&&e(t).mail_ses_secret.$errors[0].$message,required:""},{default:s(()=>[l(b,{modelValue:e(i).sesConfig.mail_ses_secret,"onUpdate:modelValue":n[15]||(n[15]=r=>e(i).sesConfig.mail_ses_secret=r),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:e(d),name:"mail_ses_secret",autocomplete:"off",invalid:e(t).sesConfig.mail_ses_secret.$error,onInput:n[16]||(n[16]=r=>e(t).sesConfig.mail_ses_secret.$touch())},{right:s(()=>[e(m)?(q(),B(_,{key:0,class:"mr-1 text-gray-500 cursor-pointer",name:"EyeOffIcon",onClick:n[13]||(n[13]=r=>O(m)?m.value=!e(m):m=!e(m))})):(q(),B(_,{key:1,class:"mr-1 text-gray-500 cursor-pointer",name:"EyeIcon",onClick:n[14]||(n[14]=r=>O(m)?m.value=!e(m):m=!e(m))}))]),_:1},8,["modelValue","content-loading","type","invalid"])]),_:1},8,["label","content-loading","error"])]),_:1}),F("div",le,[l(p,{disabled:a.isSaving,"content-loading":a.isFetchingInitialData,loading:a.isSaving,variant:"primary",type:"submit"},{left:s(r=>[a.isSaving?A("",!0):(q(),B(_,{key:0,name:"SaveIcon",class:z(r.class)},null,8,["class"]))]),default:s(()=>[S(" "+k(o.$t("general.save")),1)]),_:1},8,["disabled","content-loading","loading"]),Q(o.$slots,"default")])],40,oe)}}},se=["onSubmit"],me={class:"flex mt-8"},J={props:{configData:{type:Object,require:!0,default:Object},isSaving:{type:Boolean,require:!0,default:!1},isFetchingInitialData:{type:Boolean,require:!0,default:!1},mailDrivers:{type:Array,require:!0,default:Array}},emits:["submit-data","on-change-driver"],setup(a,{emit:D}){const $=a,i=E(),{t:u}=T(),m=h(()=>({basicMailConfig:{mail_driver:{required:f.withMessage(u("validation.required"),C)},from_mail:{required:f.withMessage(u("validation.required"),C),email:f.withMessage(u("validation.email_incorrect"),x)},from_name:{required:f.withMessage(u("validation.required"),C)}}})),y=P(m,h(()=>i));L(()=>{for(const d in i.basicMailConfig)$.configData.hasOwnProperty(d)&&i.$patch(I=>{I.basicMailConfig[d]=$.configData[d]})});async function V(){return y.value.basicMailConfig.$touch(),y.value.basicMailConfig.$invalid||D("submit-data",i.basicMailConfig),!1}function t(){y.value.basicMailConfig.mail_driver.$touch(),D("on-change-driver",i.basicMailConfig.mail_driver)}return(d,I)=>{const g=c("BaseMultiselect"),o=c("BaseInputGroup"),n=c("BaseInput"),M=c("BaseInputGrid"),v=c("BaseIcon"),b=c("BaseButton");return q(),G("form",{onSubmit:N(V,["prevent"])},[l(M,null,{default:s(()=>[l(o,{label:d.$t("settings.mail.driver"),"content-loading":a.isFetchingInitialData,error:e(y).basicMailConfig.mail_driver.$error&&e(y).basicMailConfig.mail_driver.$errors[0].$message,required:""},{default:s(()=>[l(g,{modelValue:e(i).basicMailConfig.mail_driver,"onUpdate:modelValue":[I[0]||(I[0]=_=>e(i).basicMailConfig.mail_driver=_),t],"content-loading":a.isFetchingInitialData,options:a.mailDrivers,"can-deselect":!1,invalid:e(y).basicMailConfig.mail_driver.$error},null,8,["modelValue","content-loading","options","invalid"])]),_:1},8,["label","content-loading","error"]),l(o,{label:d.$t("settings.mail.from_mail"),"content-loading":a.isFetchingInitialData,error:e(y).basicMailConfig.from_mail.$error&&e(y).basicMailConfig.from_mail.$errors[0].$message,required:""},{default:s(()=>[l(n,{modelValue:e(i).basicMailConfig.from_mail,"onUpdate:modelValue":I[1]||(I[1]=_=>e(i).basicMailConfig.from_mail=_),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"from_mail",invalid:e(y).basicMailConfig.from_mail.$error,onInput:I[2]||(I[2]=_=>e(y).basicMailConfig.from_mail.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"]),l(o,{label:d.$t("settings.mail.from_name"),"content-loading":a.isFetchingInitialData,error:e(y).basicMailConfig.from_name.$error&&e(y).basicMailConfig.from_name.$errors[0].$message,required:""},{default:s(()=>[l(n,{modelValue:e(i).basicMailConfig.from_name,"onUpdate:modelValue":I[3]||(I[3]=_=>e(i).basicMailConfig.from_name=_),modelModifiers:{trim:!0},"content-loading":a.isFetchingInitialData,type:"text",name:"name",invalid:e(y).basicMailConfig.from_name.$error,onInput:I[4]||(I[4]=_=>e(y).basicMailConfig.from_name.$touch())},null,8,["modelValue","content-loading","invalid"])]),_:1},8,["label","content-loading","error"])]),_:1}),F("div",me,[l(b,{"content-loading":a.isFetchingInitialData,disabled:a.isSaving,loading:a.isSaving,variant:"primary",type:"submit"},{left:s(_=>[a.isSaving?A("",!0):(q(),B(v,{key:0,class:z(_.class),name:"SaveIcon"},null,8,["class"]))]),default:s(()=>[S(" "+k(d.$t("general.save")),1)]),_:1},8,["content-loading","disabled","loading"]),Q(d.$slots,"default")])],40,se)}}},de={class:"flex justify-between w-full"},ue=["onSubmit"],ge={class:"p-4 md:p-8"},fe={class:"z-0 flex justify-end p-4 border-t border-gray-200 border-solid"},ce={setup(a){let D=j(!1),$=R({to:"",subject:"",message:""});const i=W(),u=E(),{t:m}=T(),y=h(()=>i.active&&i.componentName==="MailTestModal"),V={formData:{to:{required:f.withMessage(m("validation.required"),C),email:f.withMessage(m("validation.email_incorrect"),x)},subject:{required:f.withMessage(m("validation.required"),C),maxLength:f.withMessage(m("validation.subject_maxlength"),X(100))},message:{required:f.withMessage(m("validation.required"),C),maxLength:f.withMessage(m("validation.message_maxlength"),X(255))}}},t=P(V,{formData:$});function d(){$.id="",$.to="",$.subject="",$.message="",t.value.$reset()}async function I(){if(t.value.formData.$touch(),t.value.$invalid)return!0;D.value=!0,(await u.sendTestMail($)).data&&(g(),D.value=!1)}function g(){i.closeModal(),setTimeout(()=>{i.resetModalData(),d()},300)}return(o,n)=>{const M=c("BaseIcon"),v=c("BaseInput"),b=c("BaseInputGroup"),_=c("BaseTextarea"),U=c("BaseInputGrid"),p=c("BaseButton"),r=c("BaseModal");return q(),B(r,{show:e(y),onClose:g},{header:s(()=>[F("div",de,[S(k(e(i).title)+" ",1),l(M,{name:"XIcon",class:"w-6 h-6 text-gray-500 cursor-pointer",onClick:g})])]),default:s(()=>[F("form",{action:"",onSubmit:N(I,["prevent"])},[F("div",ge,[l(U,{layout:"one-column"},{default:s(()=>[l(b,{label:o.$t("general.to"),error:e(t).formData.to.$error&&e(t).formData.to.$errors[0].$message,variant:"horizontal",required:""},{default:s(()=>[l(v,{ref:"to",modelValue:e($).to,"onUpdate:modelValue":n[0]||(n[0]=w=>e($).to=w),type:"text",invalid:e(t).formData.to.$error,onInput:n[1]||(n[1]=w=>e(t).formData.to.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"]),l(b,{label:o.$t("general.subject"),error:e(t).formData.subject.$error&&e(t).formData.subject.$errors[0].$message,variant:"horizontal",required:""},{default:s(()=>[l(v,{modelValue:e($).subject,"onUpdate:modelValue":n[2]||(n[2]=w=>e($).subject=w),type:"text",invalid:e(t).formData.subject.$error,onInput:n[3]||(n[3]=w=>e(t).formData.subject.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"]),l(b,{label:o.$t("general.message"),error:e(t).formData.message.$error&&e(t).formData.message.$errors[0].$message,variant:"horizontal",required:""},{default:s(()=>[l(_,{modelValue:e($).message,"onUpdate:modelValue":n[4]||(n[4]=w=>e($).message=w),rows:"4",cols:"50",invalid:e(t).formData.message.$error,onInput:n[5]||(n[5]=w=>e(t).formData.message.$touch())},null,8,["modelValue","invalid"])]),_:1},8,["label","error"])]),_:1})]),F("div",fe,[l(p,{variant:"primary-outline",type:"button",class:"mr-3",onClick:n[6]||(n[6]=w=>g())},{default:s(()=>[S(k(o.$t("general.cancel")),1)]),_:1}),l(p,{loading:e(D),variant:"primary",type:"submit"},{left:s(w=>[e(D)?A("",!0):(q(),B(M,{key:0,name:"PaperAirplaneIcon",class:z(w.class)},null,8,["class"]))]),default:s(()=>[S(" "+k(o.$t("general.send")),1)]),_:1},8,["loading"])])],40,ue)]),_:1},8,["show"])}}},ve={key:0,class:"mt-14"},pe={setup(a){let D=j(!1),$=j(!1);const i=E(),u=W(),{t:m}=T();V();function y(g){i.mail_driver=g,i.mailConfigData.mail_driver=g}async function V(){$.value=!0,Promise.all([await i.fetchMailDrivers(),await i.fetchMailConfig()]).then(([g])=>{$.value=!1})}const t=h(()=>i.mail_driver=="smtp"?H:i.mail_driver=="mailgun"?ae:i.mail_driver=="sendmail"?J:i.mail_driver=="ses"?re:i.mail_driver=="mail"?J:H);async function d(g){try{return D.value=!0,await i.updateMailConfig(g),D.value=!1,!0}catch(o){console.error(o)}}function I(){u.openModal({title:m("general.test_mail_conf"),componentName:"MailTestModal",size:"sm"})}return(g,o)=>{const n=c("BaseButton"),M=c("BaseSettingCard");return q(),G(Z,null,[l(ce),l(M,{title:g.$t("settings.mail.mail_config"),description:g.$t("settings.mail.mail_config_desc")},{default:s(()=>[e(i)&&e(i).mailConfigData?(q(),G("div",ve,[(q(),B(Y(e(t)),{"config-data":e(i).mailConfigData,"is-saving":e(D),"mail-drivers":e(i).mail_drivers,"is-fetching-initial-data":e($),onOnChangeDriver:o[0]||(o[0]=v=>y(v)),onSubmitData:d},{default:s(()=>[l(n,{variant:"primary-outline",type:"button",class:"ml-2","content-loading":e($),onClick:I},{default:s(()=>[S(k(g.$t("general.test_mail_conf")),1)]),_:1},8,["content-loading"])]),_:1},8,["config-data","is-saving","mail-drivers","is-fetching-initial-data"]))])):A("",!0)]),_:1},8,["title","description"])],64)}}};export{pe as default};