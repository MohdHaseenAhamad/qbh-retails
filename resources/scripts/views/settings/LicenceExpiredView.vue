<template>

	<div class="message">
		<h1>
			<b>Renew Your Licence</b> تجديد رخصتك
		</h1>

		<h2>
			Your software subscription or licence is expired!
			<br>
			انتهت صلاحية اشتراكك أو ترخيصك في البرنامج
		</h2>

		<div class="sync_icon" v-on:click="redirectToSyncLicence">
			<a href="javascript:void(0)">
				<img src="/img/sync_icon.png"> Sync Licence
			</a>
		</div>

		<form v-show="!globalStore.licence.data.client_id" @submit.prevent="upgradePlan">
	  		<button type="submit" class="upgrade_request">Licence Upgrade Request</button>
		</form>
	  	

	  <p>
	    Please contact admin to renew or active services
	    <br>
	    <span>
	      يرجى الاتصال بالمسؤول للتجديد أو الخدمات النشطة
	    </span>
	  </p>
	  <a href="tel:+966593150691">+966 59 315 0691</a>
	</div>

<UpgradeCompanyModal />

</template>


<script setup>

	import { onMounted, reactive, ref, inject, computed } from 'vue'
	import { useRoute, useRouter } from 'vue-router'
	import { useGlobalStore } from '@/scripts/stores/global'
	import { useCompanyStore } from '@/scripts/stores/company'
	import { useNotificationStore } from '@/scripts/stores/notification'
	import { useModalStore } from '@/scripts/stores/modal'
	import { useI18n } from 'vue-i18n'
	import UpgradeCompanyModal from '@/scripts/components/modal-components/UpgradeCompanyModal.vue'

	const { t } = useI18n()
	const router = useRouter()
	const globalStore = useGlobalStore()
	const companyStore = useCompanyStore()
	const modalStore = useModalStore()
	const utils = inject('utils')

	let isSaving = ref(false)

	const companyForm = reactive({
	  name: null,
	  name_ar: null,
	  logo: null,
	  letterhead: null,
	  address: {
	    address_street_1: '',
	    address_street_2: '',
	    website: '',
	    country_id: null,
	    state: '',
	    city: '',
	    phone: '',
	    zip: '',
	  },
	  address_street_1_ar: null,
	  address_street_2_ar: null,
	  state_ar: null,
	  city_ar: null,
	  phone_ar: null,
	  zip_ar: null,
	  cr:null,
	  vat:null,
	  account_name:null,
	  bank_name:null,
	  account_no:null,
	  iban:null,
	  swift_code:null,
	  cr_ar:null,
	  vat_ar:null,
	  account_name_ar:null,
	  bank_name_ar:null,
	  account_no_ar:null,
	  iban_ar:null,
	  swift_code_ar:null,
	})

	utils.mergeSettings(companyForm, {
	  ...companyStore.selectedCompany,
	})



	onMounted(() => {

		// console.log(globalStore.licence.is_trial)

		setTimeout(function(){
			if(!globalStore.licenceExpired)
				redirectToSyncLicence()
		}, 1000)
	})

	function redirectToSyncLicence(){
		// router.push({ name: 'dashboard' })
		window.location.href = window.origin+'/admin/dashboard'
	}

	globalStore.fetchLicencePlans()

	const { global } = window.i18n

	async function upgradePlan(id) {
		if(globalStore.licenceRequestSent){
			const notificationStore = useNotificationStore()
		      notificationStore.showNotification({
		        type: 'success',
		        message: global.t('settings.company_info.licence.request_already_sent'),
		      })  
			return true;
		}

		// alert(globalStore.licenceRequest.id)

		// console.log('companyStore.selectedCompany');
		// console.log(companyStore.selectedCompany);
		setLicenceForm()

		modalStore.openModal({
			title: t('settings.company_info.licence.upgrade'),
			componentName: 'UpgradeCompanyModal',
			size: 'sm',
		})
		let response = await globalStore.fetchLicencePlans()
	}

	function setLicenceForm(){
	  
	  let address = '';
	  if(companyStore.selectedCompany.address.address_street_1)
	    companyStore.selectedCompany.address.address_street_1+", "
	  address += companyStore.selectedCompany.address.address_street_2 ?? ''

	  companyStore.licenceForm.companyName = companyStore.selectedCompany.name ?? 'N/A',
	  companyStore.licenceForm.lastname = companyStore.selectedCompany.name_ar ?? 'N/A',
	  companyStore.licenceForm.address = address ?? 'N/A',
	  companyStore.licenceForm.country = companyStore.selectedCompany.address.country.name ?? 'N/A',
	  companyStore.licenceForm.state = companyStore.selectedCompany.address.state ?? 'N/A',
	  companyStore.licenceForm.city = companyStore.selectedCompany.address.city ?? 'N/A',
	  companyStore.licenceForm.pincode = companyStore.selectedCompany.address.zip ?? 'N/A',
	  companyStore.licenceForm.phone= companyStore.selectedCompany.address.phone ?? 'N/A'
	}

</script>

<style type="text/css">
.upgrade_request:hover {font-weight: bold; background: #000;}
.upgrade_request {
    color: #fff;
    background-color: #464646;
    padding: 10px;
    border-radius: 5px;
    min-width: 250px;
}
.sync_icon a:hover {
    transform: scale(1.1);
    transition: all 0.2s;
}
.sync_icon a img {
    width: 20px;
}
.sync_icon a {
    background: #fff;
    display: inline-block !important;
    padding: 10px;
    border: 1px solid;
    border-radius: 5px;
}
.sync_icon img {
    float: left;
    margin-right: 5px;
}
.sync_icon {
    float: left;
    width: 100%;
    margin-bottom: 25px;
}
.message {
  text-align: left;
  margin: 5em 5em;
  padding: 0 2em;
}
.message h1 {
  color: #3698DC;
  font-size: 3vw !important;
  font-weight: 400;
  padding-top: 50px;
}
.message h2 {
	font-size: 25px;
}
.message p {
	margin-top: 50px;
	color: #262C34;
	font-size: 1.3em;
	font-weight: lighter;
	line-height: 1.1em;
}
.message a {
	font-weight: bold; margin-top: 25px; display: block;
}
</style>