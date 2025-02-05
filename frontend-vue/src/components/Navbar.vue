<script setup>
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
</script>
<template>
    <!-- Navigation drawer -->
    <v-card>
        <v-layout>
        <v-navigation-drawer
            expand-on-hover
            rail
            id="nav"
        >
            <v-list>
            <v-list-item
                prepend-avatar="https://randomuser.me/api/portraits/women/24.jpg"
                subtitle="username@gmail.com"
                title="Username"
            ></v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav v-for="option in navOptions">                
                <v-list-item class="nav-item" :prepend-icon="option.icon" :title="option.title" :value="option.title" :to="option.route"></v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <!-- Toolbar -->
            <v-toolbar
                id="toolbar"
                :title="appTitle"
            ></v-toolbar>
            <!-- Toolbar -->

            <!-- Content -->
            <router-view></router-view>            
            <!-- Content -->            
        </v-main>
        </v-layout>
    </v-card>
    <!-- Navigation drawer -->
</template>