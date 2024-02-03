const { default: axios } = require('axios');


require('./bootstrap');

import { offset } from '@popperjs/core';
import VueApexCharts from 'vue-apexcharts'
window.Vue = require('vue').default;
import Swal from 'sweetalert2'
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';

import VueTimeago from 'vue-timeago'

Vue.use(VueTimeago, {
  name: 'Timeago', // Component name, `Timeago` by default
  locale: 'en', // Default locale
})

Vue.component('v-select', vSelect)
Vue.component('apexchart', VueApexCharts)
Vue.component('top-notification-component', require('./components/Notification/MainComponent.vue').default);
Vue.component('project-material-select2-component', require('./components/Project/ProjectMaterialForm/MainComponent.vue').default);
Vue.component('project-expenses-chart-component', require('./components/Project/ExpensesChart/MainComponent.vue').default);

Vue.component('material-delivery-origin-select-component', require('./components/Material/DeliveryForm/OriginSelect.vue').default);
Vue.component('material-delivery-destination-select-component', require('./components/Material/DeliveryForm/DestinationSelect.vue').default);
Vue.component('material-delivery-material-component', require('./components/Material/DeliveryForm/Materials.vue').default);

Vue.component('material-restock-material-component', require('./components/Material/Restock/Materials.vue').default);

Vue.component('expense-create-form-component', require('./components/Expense/Create/MainComponent.vue').default);

Vue.component('petty-cash-item-inputs', require('./components/Cashier/PettyCash/ItemsInput/MainComponent.vue').default);
// Vue.component('material-delivery-form-component', require('./components/Material/DeliveryFrom/MainComponent.vue').default);

Vue.component('salary-helper-form', require('./components/Cashier/Salary/HelperFormComponent.vue').default);

Vue.component('accomplishment-component', require('./components/Project/Accomplishment/MainComponent.vue').default);
Vue.component('accomplishment-item-form', require('./components/Project/Accomplishment/CreateForm/MainComponent.vue').default);
Vue.component('accomplishment-achievement-input', require('./components/Project/Accomplishment/AchievementInput/MainComponent.vue').default);

const app = new Vue({
    el: '#app',
});

/* ===== Enable Bootstrap Popover (on element  ====== */

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

/* ==== Enable Bootstrap Alert ====== */
var alertList = document.querySelectorAll('.alert')

// alertList.forEach(function (alert) {
//   new bootstrap.Alert(alert)
// });

/* ===== Responsive Sidepanel ====== */
const sidePanelToggler = document.getElementById('sidepanel-toggler'); 
const sidePanel = document.getElementById('app-sidepanel');  
const sidePanelDrop = document.getElementById('sidepanel-drop'); 
const sidePanelClose = document.getElementById('sidepanel-close'); 
const selectionFilters = document.querySelectorAll('[data-filtering-select="true"]');
const SubContractsCheckbox = document.querySelectorAll('input[name="sub_contact_id"]');


if(SubContractsCheckbox){
	SubContractsCheckbox.forEach(function(checkbox){
		checkbox.addEventListener("change", function(){
			if (this.checked) {
				console.log("Checkbox is checked..");
			  } else {
				console.log("Checkbox is not checked..");
			  }
		})
	});
}

window.addEventListener('load', function(){
	responsiveSidePanel(); 
});

window.addEventListener('resize', function(){
	responsiveSidePanel(); 
});


function responsiveSidePanel() {
    let w = window.innerWidth;
	if(sidePanel){
		if(w >= 1200) {
			// if larger 
			//console.log('larger');
			sidePanel.classList.remove('sidepanel-hidden');
			sidePanel.classList.add('sidepanel-visible');
			
		} else {
			// if smaller
			//console.log('smaller');
			sidePanel.classList.remove('sidepanel-visible');
			sidePanel.classList.add('sidepanel-hidden');
		}
	}
};

if(sidePanelToggler){
	sidePanelToggler.addEventListener('click', () => {
		if (sidePanel.classList.contains('sidepanel-visible')) {
			sidePanel.classList.remove('sidepanel-visible');
			sidePanel.classList.add('sidepanel-hidden');
			
		} else {
			sidePanel.classList.remove('sidepanel-hidden');
			sidePanel.classList.add('sidepanel-visible');
		}
	});
}



if(sidePanelClose){
	sidePanelClose.addEventListener('click', (e) => {
		e.preventDefault();
		sidePanelToggler.click();
	});
}

if(sidePanelDrop){
	sidePanelDrop.addEventListener('click', (e) => {
		sidePanelToggler.click();
	});
}

if(selectionFilters){
	selectionFilters.forEach(e => {
		e.addEventListener('change', (element) => {
			e.closest('form').submit();
		})
	})
}

const overlayedForms = document.querySelectorAll('.overlayed-form');
overlayedForms.forEach(e => {
	e.addEventListener("submit", function(event){
		document.body.classList.add("overlay-body")
		document.getElementById("overlay-box").classList.add("show-overlay")
	})
})

const permissionSwitches = document.querySelectorAll('.role-permission-switch');
permissionSwitches.forEach(e => {
	e.addEventListener("change", function(event){
		var checked = e.checked;
		var permission = e.dataset.permission;
		var role = e.dataset.role;
		axios.post('/api/update-role-permission',{
			checked: checked,
			role_id: role,
			permission: permission
		})
		.then(e => {
			
		})
		.catch( e => {
			console.log(e.response.data);
		})
	})
})


// const expenseTypeSelector = document.querySelectorAll('.expense-type-selector');
// const expenseExtraInputs = document.querySelectorAll('.expense-extra-inputs');
// const expenseUnitQuantity = document.querySelectorAll('[name="unit_quantity"]')[0];
// const expenseUnitPrice = document.querySelectorAll('[name="unit_price"]')[0];
// const expenseAmount = document.querySelectorAll('[name="amount"]')[0];

// expenseTypeSelector.forEach(e => {
// 	e.addEventListener('change', (event) =>{
// 		if(e.value === "material" || e.value === "rental equipment"){
// 			expenseExtraInputs[0].classList.remove("d-none")
// 			expenseUnitQuantity.required = true
// 			expenseUnitPrice.required = true
// 		}else{
// 			expenseExtraInputs[0].classList.add("d-none")
// 			expenseUnitQuantity.value = 1
// 			expenseUnitPrice.value = ""
// 			expenseUnitQuantity.required = false
// 			expenseUnitPrice.required = false
// 		}
// 		// console.log(expenseUnitPrice.value)
// 	})
// })

// if(expenseUnitQuantity){
// 	expenseUnitQuantity.addEventListener("change", function(){
// 		expenseAmount.value = expenseUnitPrice.value * expenseUnitQuantity.value
// 	})

// 	expenseUnitQuantity.addEventListener("keyup", function(){
// 		expenseAmount.value = expenseUnitPrice.value * expenseUnitQuantity.value
// 	})
// }

// if(expenseUnitPrice){
// 	expenseUnitPrice.addEventListener("keyup", function(){
// 		expenseAmount.value = expenseUnitPrice.value * expenseUnitQuantity.value
// 	})
	
// 	expenseUnitPrice.addEventListener("change", function(){
// 		expenseAmount.value = expenseUnitPrice.value * expenseUnitQuantity.value
// 	})
// }


const toogleDarkMode = document.querySelectorAll('#toogleDarkMode')[0];
if(toogleDarkMode){
	toogleDarkMode.addEventListener("change", function(e){
		var checked = toogleDarkMode.checked;
		axios.post('/dark-mode/update', {
				darkmode: checked
			})
			.then(()=>{
				var element = document.body;
				element.classList.toggle("dark");
			})
			.catch( error => {
				console.log(error)
			})
	})
}



const addStockModalButton = document.getElementById('addStockModal');
if(addStockModalButton){
	var addStockModal = new bootstrap.Modal(addStockModalButton);
	var addStockButtons = document.querySelectorAll(".add-stock-btn");
	if(addStockButtons.length > 0){
		addStockButtons.forEach( e => {
			e.addEventListener("click", function(){
				document.getElementById("material_id_add_stock").value = e.dataset.material_id
				addStockModal.show();
			})
		})
	}

}