<script setup>
import { ref, computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { images } from '@js/utils/files.js';

const page = usePage();

const notifications = computed(() => {
    const list = page.props.notifications ?? [];

    return [...list]
        .sort((a, b) => (b.created_at ?? "") > (a.created_at ?? "") ? 1 : -1)
        .slice(0, 3);
});

const unread_count = computed(
    () => notifications.value.filter((n) => !n.is_read).length,
);

const notifications_link = computed(
    () => page.props.notifications_link ?? route("index"),
);

const dashboard_link = computed(() => {
    const user_type = page.props.user?.user_type;

    if (user_type == 'developer') {
        return route('developer');
    }

    return route('owner');
});

function logout() {
    router.post(route('logout'));
}
</script>
<template>
    <v-app>
        <v-layout>
            <v-app-bar elevation="1">
                <v-img
                    :src="images.avexe_name_logo"
                    max-width="110"
                    contain
                    class="mx-4 cursor-pointer"
                    @click="router.visit(route('index'))"
                />
                <v-spacer />
                <!-- DROPDOWN NOTIFICAÇÕES -->
                <v-menu
                    location="bottom end"
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ props }">
                        <v-btn
                            variant="text"
                            class="mr-2"
                            v-bind="props"
                        >
                            <v-badge
                                :model-value="unread_count > 0"
                                :content="unread_count"
                                color="error"
                                location="top end"
                            >
                                <v-icon icon="mdi-bell-outline"></v-icon>
                            </v-badge>
                        </v-btn>
                    </template>
                    <v-card min-width="340" max-width="380">
                        <v-card-title class="d-flex align-center">
                            <span class="font-weight-bold">Notificações</span>
                            <v-spacer></v-spacer>
                            <v-btn
                                icon="mdi-check-all"
                                size="small"
                                variant="text"
                                color="primary"
                                title="Marcar todas como lidas"
                            ></v-btn>
                        </v-card-title>
                        <v-divider></v-divider>
                        <template v-if="notifications.length">
                            <v-list density="comfortable" max-height="320">
                                <v-list-item
                                    v-for="notification in notifications"
                                    :key="notification.id"
                                    class="pa-2"
                                >
                                    <template v-slot:prepend>
                                        <v-avatar
                                            :color="
                                                notification.is_read
                                                    ? 'grey-lighten-2'
                                                    : 'primary'
                                            "
                                            size="40"
                                            class="mr-2"
                                        >
                                            <v-icon
                                                :icon="
                                                    notification.is_read
                                                        ? 'mdi-bell-outline'
                                                        : 'mdi-bell'
                                                "
                                                :color="
                                                    notification.is_read
                                                        ? 'grey'
                                                        : 'white'
                                                "
                                            ></v-icon>
                                        </v-avatar>
                                    </template>
                                    <v-list-item-title class="font-weight-bold">
                                        {{ notification.title }}
                                    </v-list-item-title>
                                    <v-list-item-subtitle
                                        class="notification-content"
                                    >
                                        {{ notification.content }}
                                    </v-list-item-subtitle>
                                </v-list-item>
                            </v-list>
                            <v-divider></v-divider>
                            <v-list-item
                                @click="router.visit(notifications_link)"
                                class="text-center"
                            >
                                <v-list-item-title
                                    class="text-primary font-weight-bold"
                                >
                                    Ver todas
                                </v-list-item-title>
                            </v-list-item>
                        </template>
                        <div v-else class="pa-8 text-center">
                            <v-icon
                                icon="mdi-bell-sleep-outline"
                                size="48"
                                color="grey-lighten-1"
                            ></v-icon>
                            <p class="text-subtitle-1 mt-3 text-medium-emphasis">
                                Nenhuma notificação no momento
                            </p>
                        </div>
                    </v-card>
                </v-menu>
                <!-- DROPDOWN PERFIL -->
                <v-menu offset-y location="bottom end">
                    <template v-slot:activator="{ props }">
                        <v-avatar
                            size="40"
                            color="primary"
                            class="mx-4 cursor-pointer"
                            v-bind="props"
                        >
                            <v-img
                                v-if="page.props.user?.profile_url"
                                :src="page.props.user.profile_url"
                                cover
                            ></v-img>
                            <v-icon
                                v-else
                                icon="mdi-account"
                                color="white"
                            ></v-icon>
                        </v-avatar>
                    </template>
                    <v-list min-width="220">
                        <v-list-item class="py-2">
                            <v-list-item-title class="font-weight-bold">
                                {{ page.props.user?.name }}
                            </v-list-item-title>
                            <v-list-item-subtitle>
                                {{ page.props.user?.email }}
                            </v-list-item-subtitle>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item @click="router.visit(dashboard_link)">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-view-dashboard"></v-icon>
                            </template>
                            <v-list-item-title>Dashboard</v-list-item-title>
                        </v-list-item>
                        <v-list-item
                            @click="router.visit(route('customer.profileView'))"
                        >
                            <template v-slot:prepend>
                                <v-icon icon="mdi-account-circle"></v-icon>
                            </template>
                            <v-list-item-title>Perfil</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="router.visit(route('index'))">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-home"></v-icon>
                            </template>
                            <v-list-item-title>Início</v-list-item-title>
                        </v-list-item>
                        <v-divider></v-divider>
                        <v-list-item @click="logout">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-logout"></v-icon>
                            </template>
                            <v-list-item-title class="text-error">
                                Sair
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-app-bar>
            <v-main>
                <v-container fluid>
                    <slot></slot>
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>
<style scoped lang="scss">
.notification-content {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>