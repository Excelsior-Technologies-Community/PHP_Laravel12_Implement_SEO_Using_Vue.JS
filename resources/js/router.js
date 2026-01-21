import { createRouter, createWebHistory } from 'vue-router';

// ADMIN PAGES
import ProductIndex from './pages/ProductIndex.vue'
import ProductCreate from './pages/ProductCreate.vue'
import ProductEdit from './pages/ProductEdit.vue'

// CUSTOMER PAGES
import CustomerIndex from './pages/customer/CustomerIndex.vue'
import CustomerDetails from './pages/customer/CustomerDetails.vue'

const routes = [
    // -----------------------------------
    // ADMIN ROUTES
    // -----------------------------------
    { path: '/products', component: ProductIndex },
    { path: '/products/create', component: ProductCreate },
    { path: '/products/edit/:id', component: ProductEdit, props: true },

    // -----------------------------------
    // CUSTOMER ROUTES
    // -----------------------------------
    { path: '/customer/products', component: CustomerIndex },
    { path: '/customer/products/:id', component: CustomerDetails, props: true },
];

export default createRouter({
    history: createWebHistory(),
    routes
});
