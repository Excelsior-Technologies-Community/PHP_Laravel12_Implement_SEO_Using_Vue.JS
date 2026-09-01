import { createRouter, createWebHistory } from 'vue-router';

// ADMIN
import ProductIndex from './pages/ProductIndex.vue';
import ProductCreate from './pages/ProductCreate.vue';
import ProductEdit from './pages/ProductEdit.vue';

// CUSTOMER
import CustomerIndex from './pages/customer/CustomerIndex.vue';
import CustomerDetails from './pages/customer/CustomerDetails.vue';

const routes = [

    // ADMIN
    {
        path: '/products',
        component: ProductIndex
    },

    {
        path: '/products/create',
        component: ProductCreate
    },

    {
        path: '/products/edit/:id',
        component: ProductEdit,
        props: true
    },

    // CUSTOMER PRODUCT LIST
    {
        path: '/customer/products',
        component: CustomerIndex
    },

    // SEO FRIENDLY CUSTOMER PRODUCT
    {
        path: '/product/:slug',
        component: CustomerDetails,
        props: true
    },

    // OLD CUSTOMER URL - kept for compatibility
    {
        path: '/customer/products/:id',
        component: CustomerDetails,
        props: true
    },

];

export default createRouter({

    history: createWebHistory(),

    routes,

});