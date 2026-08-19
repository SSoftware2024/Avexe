<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useDisplay } from 'vuetify';
import { router, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { images } from '@js/utils/files.js';

const page = usePage();
const { mdAndUp } = useDisplay();

const drawer = ref(false);

onMounted(() => {
    drawer.value = mdAndUp.value;
});

watch(
    () => page.url,
    () => {
        if (!mdAndUp.value) {
            drawer.value = false;
        }
    },
);

const fake_notifications = [
    {
        id: 1,
        title: 'Novo agendamento',
        content: 'Maria Silva acabou de agendar uma consulta para amanhã às 14h.',
        is_read: false,
        created_at: '2026-08-19 10:30:00',
    },
    {
        id: 2,
        title: 'Pagamento recebido',
        content: 'O pagamento do serviço Corte de cabelo foi confirmado com sucesso.',
        is_read: false,
        created_at: '2026-08-18 16:45:00',
    },
    {
        id: 3,
        title: 'Avaliação recebida',
        content: 'João Pedro avaliou sua empresa com 5 estrelas.',
        is_read: true,
        created_at: '2026-08-18 09:15:00',
    },
];

const notifications = computed(() => {
    const list = page.props.notifications ?? fake_notifications;

    return [...list]
        .sort((a, b) => (b.created_at ?? '') > (a.created_at ?? '') ? 1 : -1)
        .slice(0, 3);
});

const unread_count = computed(
    () => notifications.value.filter((n) => !n.is_read).length,
);

const notifications_link = computed(
    () => page.props.notifications_link ?? route('index'),
);

const dashboard_link = computed(() => {
    const user_type = page.props.user?.user_type;

    if (user_type == 'developer') {
        return route('developer');
    }

    return route('owner');
});

const nav_sections = computed(() => [
    {
        title: 'Gerenciamento',
        items: [
            {
                title: 'Dashboard',
                icon: 'mdi-view-dashboard-outline',
                to: dashboard_link.value,
                active_names: ['owner', 'developer'],
            },
            {
                title: 'Agendamentos',
                icon: 'mdi-calendar-week-outline',
                children: [
                    {
                        title: 'Hoje',
                        icon: 'mdi-calendar-today',
                    },
                    {
                        title: 'Próximos',
                        icon: 'mdi-calendar-month',
                    },
                    {
                        title: 'Histórico',
                        icon: 'mdi-calendar-check',
                    },
                ],
            },
            {
                title: 'Clientes',
                icon: 'mdi-account-group-outline',
                children: [
                    {
                        title: 'Listar clientes',
                        icon: 'mdi-account-multiple-outline',
                    },
                    {
                        title: 'Novo cliente',
                        icon: 'mdi-account-plus-outline',
                    },
                ],
            },
            {
                title: 'Serviços',
                icon: 'mdi-content-cut',
                children: [
                    {
                        title: 'Serviços e preços',
                        icon: 'mdi-tag-outline',
                    },
                    {
                        title: 'Produtos',
                        icon: 'mdi-package-variant',
                    },
                ],
            },
        ],
    },
    {
        title: 'Relatórios',
        items: [
            { title: 'Relatórios', icon: 'mdi-chart-box-outline' },
            { title: 'Financeiro', icon: 'mdi-cash-multiple' },
        ],
    },
    {
        title: 'Configurações',
        items: [
            { title: 'Perfil', icon: 'mdi-account-circle-outline', to: route('customer.profileView'), active_names: ['customer.profileView'] },
            { title: 'Início', icon: 'mdi-home-outline', to: route('index'), active_names: ['index'] },
        ],
    },
]);

function is_active(item) {
    return item.active_names?.some((name) => route().current(name)) ?? false;
}

function navigate(item) {
    if (item.to) {
        router.visit(item.to);
    }
}

function logout() {
    router.post(route('logout'));
}

function mark_as_read(notification) {
    if (!notification.is_read) {
        notification.is_read = true;
    }
}
</script>
<template>
    <v-app>
        <v-layout>
            <!-- SIDEBAR -->
            <v-navigation-drawer
                v-model="drawer"
                :temporary="!mdAndUp"
                width="280"
                class="auth-sidebar"
            >
                <div class="sidebar-scroll">
                    <v-list nav density="compact">
                        <template
                            v-for="section in nav_sections"
                            :key="section.title"
                        >
                            <div class="sidebar-section-title px-4 pt-3 pb-1">
                                {{ section.title }}
                            </div>
                            <template
                                v-for="item in section.items"
                                :key="item.title"
                            >
                                <v-list-group
                                    v-if="item.children"
                                    :value="item.title"
                                >
                                    <template v-slot:activator="{ props }">
                                        <v-list-item
                                            v-bind="props"
                                            :title="item.title"
                                            :prepend-icon="item.icon"
                                        ></v-list-item>
                                    </template>
                                    <v-list-item
                                        v-for="child in item.children"
                                        :key="child.title"
                                        :title="child.title"
                                        :prepend-icon="child.icon"
                                        :active="is_active(child)"
                                        :variant="
                                            is_active(child) ? 'flat' : undefined
                                        "
                                        color="primary"
                                        @click="navigate(child)"
                                    ></v-list-item>
                                </v-list-group>
                                <v-list-item
                                    v-else
                                    :title="item.title"
                                    :prepend-icon="item.icon"
                                    :active="is_active(item)"
                                    :variant="
                                        is_active(item) ? 'flat' : undefined
                                    "
                                    color="primary"
                                    @click="navigate(item)"
                                ></v-list-item>
                            </template>
                        </template>
                    </v-list>
                </div>

                <template #append>
                    <v-divider></v-divider>
                    <v-list nav density="compact">
                        <v-list-item
                            title="Sair"
                            prepend-icon="mdi-logout"
                            class="mx-2 my-2 logout-item"
                            @click="logout"
                        ></v-list-item>
                    </v-list>
                </template>
            </v-navigation-drawer>
            <!-- FIM SIDEBAR -->
            <v-app-bar elevation="1">
                <v-btn
                    icon="mdi-menu"
                    variant="text"
                    class="ml-2"
                    @click="drawer = !drawer"
                ></v-btn>
                <v-img
                    :src="images.avexe_name_logo"
                    max-width="110"
                    contain
                    class="mx-2 cursor-pointer"
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
                        <v-btn variant="text" class="mr-2" v-bind="props">
                            <v-badge :content="unread_count" color="error" location="top end">
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
                                    :class="{
                                        'notification-unread':
                                            !notification.is_read,
                                    }"
                                    @click="mark_as_read(notification)"
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
                        <v-list-item @click="logout" class="logout-item">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-logout"></v-icon>
                            </template>
                            <v-list-item-title>Sair</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-app-bar>
            <v-main>
                <v-container fluid class="pa-4">
                    <v-card class="pa-4" elevation="1">
                        <slot></slot>
                    </v-card>
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>
<style scoped lang="scss">
.auth-sidebar {
    background-color: var(--v-theme-surface-variant);

    :deep(.sidebar-scroll) {
        flex: 1;
        overflow-y: auto;
    }

    .sidebar-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        color: rgb(var(--v-theme-secondary));
    }

    :deep(.v-list-item:not(.logout-item):not(.v-list-item--active):hover) {
        background-color: rgba(var(--v-theme-secondary), 0.15);
        color: #f25922;

        .v-list-item-title,
        .v-icon {
            color: #f25922;
        }
    }
}

:deep(.v-main) {
    background-color: var(--v-theme-surface-variant);
}

.notification-content {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-unread {
    background-color: rgba(var(--v-theme-primary), 0.06);
    cursor: pointer;
}

.logout-item {
    background-color: rgb(var(--v-theme-error));
    border-radius: 8px;
    color: #fff;

    :deep(.v-list-item-title),
    :deep(.v-icon) {
        color: #fff !important;
    }

    &:hover {
        background-color: #ff1744;
    }
}
</style>