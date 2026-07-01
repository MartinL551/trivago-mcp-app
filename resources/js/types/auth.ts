import type { CurrencyCode } from './currency';

export type User = {
    id: number;
    name: string;
    provider: string;
    provider_id?: string | null;
    preferred_currency?: CurrencyCode | null;
    avatar?: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
};

export type Auth = {
    user: User;
};

export type TwoFactorConfigContent = {
    title: string;
    description: string;
    buttonText: string;
};
