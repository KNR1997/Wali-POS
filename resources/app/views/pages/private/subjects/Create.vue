<template>
    <Page :title="page.title" :breadcrumbs="page.breadcrumbs" :actions="page.actions" @action="onAction">
        <Panel>
            <Form id="create-subject" @submit.prevent="onSubmit">
                <TextInput class="mb-4" type="text" :required="true" name="name" v-model="form.name" :label="trans('subjects.labels.name')"/>
                <TextInput class="mb-4" type="text" :required="true" name="code" v-model="form.code" :label="trans('subjects.labels.code')"/>
            </Form>
        </Panel>
    </Page>
</template>

<script>
import {defineComponent, reactive} from "vue";
import {trans} from "@/helpers/i18n";
import {useAuthStore} from "@/stores/auth";
import Button from "@/views/components/input/Button";
import TextInput from "@/views/components/input/TextInput";
import Dropdown from "@/views/components/input/Dropdown";
import Alert from "@/views/components/Alert";
import Panel from "@/views/components/Panel";
import Page from "@/views/layouts/Page";
import FileInput from "@/views/components/input/FileInput";
import SubjectService from "@/services/SubjectService";
import {clearObject, reduceProperties} from "@/helpers/data";
import {toUrl} from "@/helpers/routing";
import Form from "@/views/components/Form";

export default defineComponent({
    components: {Form, FileInput, Panel, Alert, Dropdown, TextInput, Button, Page},
    setup() {
        const {user} = useAuthStore();
        const form = reactive({
            name: '',
            code: '',
        });

        const page = reactive({
            id: 'create_subjects',
            title: trans('global.pages.subjects_create'),
            filters: false,
            breadcrumbs: [
                {
                    name: trans('global.pages.subjects'),
                    to: toUrl('/subjects/list'),

                },
                {
                    name: trans('global.pages.subjects_create'),
                    active: true,
                }
            ],
            actions: [
                {
                    id: 'back',
                    name: trans('global.buttons.back'),
                    icon: "fa fa-angle-left",
                    to: toUrl('/subjects/list'),
                    theme: 'outline',
                },
                {
                    id: 'submit',
                    name: trans('global.buttons.save'),
                    icon: "fa fa-save",
                    type: 'submit',
                }
            ]
        });

        const service = new SubjectService();

        function onAction(data) {
            switch(data.action.id) {
                case 'submit':
                    onSubmit();
                    break;
            }
        }

        function onSubmit() {
            service.handleCreate('create-user', reduceProperties(form, 'roles', 'id')).then(() => {
                clearObject(form)
            })
            return false;
        }

        return {
            trans,
            user,
            form,
            page,
            onSubmit,
            onAction,
        }
    }
})
</script>

<style scoped>

</style>
