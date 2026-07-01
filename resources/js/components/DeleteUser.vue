<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { TriangleAlert, Trash2 } from 'lucide-vue-next';
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
</script>

<template>
    <section
        class="space-y-5 rounded border-3 border-[var(--card-border)] bg-[var(--card-bg)] p-6 shadow-[var(--card-shadow)]"
    >
        <Heading
            title="Delete account"
            description="Delete your account and all of its resources"
        />
        <div
            class="space-y-5 rounded border border-[var(--danger-border)] bg-[var(--danger-bg)] p-5"
        >
            <div class="flex items-start gap-3 text-[var(--danger-text)]">
                <TriangleAlert class="mt-1 size-5 shrink-0" />
                <div class="space-y-1">
                    <p class="font-medium">Warning</p>
                    <p class="text-sm">
                        Please proceed with caution, this cannot be undone.
                    </p>
                </div>
            </div>
            <Dialog>
                <DialogTrigger as-child>
                    <Button
                        class="border border-[var(--danger)] bg-[var(--danger)] text-white hover:bg-[var(--danger-hover)]"
                        data-test="delete-user-button"
                    >
                        <Trash2 class="size-4" />
                        Delete account
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <Form
                        v-bind="ProfileController.destroy.form()"
                        reset-on-success
                        :options="{
                            preserveScroll: true,
                        }"
                        class="space-y-6"
                    >
                        <DialogHeader class="space-y-3">
                            <DialogTitle>
                                Are you sure you want to delete your account?
                            </DialogTitle>
                            <DialogDescription>
                                Once your account is deleted, all of its
                                resources and data will also be permanently
                                deleted.
                            </DialogDescription>
                        </DialogHeader>

                        <DialogFooter class="gap-2">
                            <DialogClose as-child>
                                <Button variant="secondary">
                                    Cancel
                                </Button>
                            </DialogClose>

                            <Button
                                type="submit"
                                class="border border-[var(--danger)] bg-[var(--danger)] text-white hover:bg-[var(--danger-hover)]"
                                data-test="confirm-delete-user-button"
                            >
                                Delete account
                            </Button>
                        </DialogFooter>
                    </Form>
                </DialogContent>
            </Dialog>
        </div>
    </section>
</template>
