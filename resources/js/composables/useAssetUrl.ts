import { usePage } from '@inertiajs/vue3';

export function useAssetUrl() {
    const page = usePage();
    const appUrl = page.props.ziggy?.url || '';

    const getAssetUrl = (path: string | null): string => {
        if (!path) {
            return ''; // Or return a default image URL
        }

        // If it's already a full URL (e.g., S3 URL), return as is
        if (path.startsWith('http://') || path.startsWith('https://')) {
            return path;
        }

        // For local storage, prepend the storage path
        return `${appUrl}/storage/${path}`;
    };

    return {
        getAssetUrl,
    };
}