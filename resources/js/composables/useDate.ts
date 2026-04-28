export function useDate() {
    const formatDate = (date: string | Date | null): string => {
        if (!date) return '—';
        return new Intl.DateTimeFormat('en-AU', { dateStyle: 'medium' }).format(new Date(date));
    };

    const formatDateTime = (date: string | Date | null): string => {
        if (!date) return '—';
        return new Intl.DateTimeFormat('en-AU', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(date));
    };

    return { formatDate, formatDateTime };
}
