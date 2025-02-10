<script setup>
    import {ref} from 'vue'
    const appTitle = "Task App";

    const navOptions = [
        {
            icon: 'mdi-home',
            title: 'Home',
            route: '/'
        },
        {
            icon: 'mdi-account-group',
            title: 'Users',
            route: '/user'
        },
        {
            icon: 'mdi-book-open-page-variant',
            title: 'My Tasks',
            route: '/task'
        }
    ]

    const drawer = ref(true)
    const rail = ref(true)
</script>
<template>
    <!-- Navigation drawer -->
    <v-card>
        <v-layout>
            <v-navigation-drawer
                expand-on-hover
                id="nav"
                v-model="drawer"
                :rail="rail && !drawer"
            >
                <v-list>
                <v-list-item
                    prepend-avatar="https://randomuser.me/api/portraits/women/50.jpg"
                    subtitle="Ramírez Reyes"
                    title="Raisa Fabiola"
                ></v-list-item>
                </v-list>

                <v-divider></v-divider>

                <v-list density="compact" nav v-for="option in navOptions">                
                    <v-list-item class="nav-item" :prepend-icon="option.icon" :title="option.title" :value="option.title" :to="option.route"></v-list-item>
                </v-list>
            </v-navigation-drawer>

            <v-main>
                <v-app-bar app class="menu-button">
                    <v-btn icon @click="drawer = !drawer">
                        <v-icon>mdi-menu</v-icon>
                    </v-btn>
                    <!-- Toolbar -->
                    <v-toolbar
                        id="toolbar"
                        :title="appTitle"
                    ></v-toolbar>
                    <!-- Toolbar -->
                </v-app-bar>                

                <!-- Content -->
                <router-view></router-view>            
                <!-- Content -->            
            </v-main>
        </v-layout>
    </v-card>
    <!-- Navigation drawer -->
</template>