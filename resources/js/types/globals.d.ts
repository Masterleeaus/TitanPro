import { AppPageProps } from '@/types/index';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}

/**
 * Titan OS context injected by resources/views/titan-os/context.blade.php.
 * Contains page-level metadata used by the Business OS shell and chat panel.
 */
interface TitanOsContext {
    panel_id: string;
    panel_path: string;
    route_name: string;
    route_path: string;
    page_title: string;
    user_id: string;
    user_role: string;
    company_id: string;
    app_key: string;
    shell_mode: string;
    [key: string]: unknown;
}

declare global {
    interface Window {
        titanOsContext?: TitanOsContext;
    }
}
