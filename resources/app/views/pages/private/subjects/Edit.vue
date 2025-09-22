<template>
    <Page :title="page.title" :breadcrumbs="page.breadcrumbs" :actions="page.actions" @action="onAction" :is-loading="page.loading">
        <Panel>
            <Form id="edit-subject" @submit.prevent="onSubmit">
                <TextInput class="mb-4" type="text" :required="true" name="name" v-model="form.name" :label="trans('subjects.labels.name')"/>
                <TextInput class="mb-4" type="text" :required="true" name="code" v-model="form.code" :label="trans('subjects.labels.code')"/>
            </Form>
        </Panel>
    </Page>
</template>

<script>
import {defineComponent, onBeforeMount, reactive, ref} from "vue";
import {trans} from "@/helpers/i18n";
import {fillObject, reduceProperties} from "@/helpers/data"
import {useRoute} from "vue-router";
import {useAuthStore} from "@/stores/auth";
import {toUrl} from "@/helpers/routing";
import SubjectService from "@/services/SubjectService";
import Button from "@/views/components/input/Button";
import TextInput from "@/views/components/input/TextInput";
import Dropdown from "@/views/components/input/Dropdown";
import Alert from "@/views/components/Alert";
import Panel from "@/views/components/Panel";
import Page from "@/views/layouts/Page";
import FileInput from "@/views/components/input/FileInput";
import Form from "@/views/components/Form";

export default defineComponent({
    components: {
        Form,
        FileInput,
        Panel,
        Alert,
        Dropdown,
        TextInput,
        Button,
        Page
    },
    setup() {
        const {user} = useAuthStore();
        const route = useRoute();
        const form = reactive({
            name: '',
            code: '',
        });

        const page = reactive({
            id: 'edit_user',
            title: trans('global.pages.subjects_edit'),
            filters: false,
            loading: true,
            breadcrumbs: [
                {
                    name: trans('global.pages.subject'),
                    to: toUrl('/subject/list'),
                },
                {
                    name: trans('global.pages.subjects_edit'),
                    active: true,
                }
            ],
            actions: [
                {
                    id: 'back',
                    name: trans('global.buttons.back'),
                    icon: "fa fa-angle-left",
                    to: toUrl('/subject/list'),
                    theme: 'outline',
                },
                {
                    id: 'submit',
                    name: trans('global.buttons.update'),
                    icon: "fa fa-save",
                    type: 'submit'
                }
            ]
        });

        const service = new SubjectService();

        onBeforeMount(() => {
            service.edit(route.params.id).then((response) => {
                fillObject(form, response.data.model);
                page.loading = false;
            })
        });

        function onAction(data) {
            switch(data.action.id) {
                case 'submit':
                    onSubmit();
                    break;
            }
        }

        function onSubmit() {
            service.handleUpdate('edit-subject', route.params.id, reduceProperties(form, ['roles'], 'id'));
            return false;
        }

        return {
            trans,
            user,
            form,
            onSubmit,
            onAction,
            page
        }
    }
})
</script>

<style scoped>

</style>
