<template>
    <div class="row">
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.title.text ? 'has-error' : ''} `">
                <label for="title">Titulo</label>
                <input type="text" id="title"  name="title" v-model="form.title" class="form-control" required>
                <p v-if="!$v.form.title.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.description.text ? 'has-error' : ''} `">
                <label for="description">Descripción</label>
                <input type="text" id="description" name="description" v-model="form.description" class="form-control" required>
                <p v-if="!$v.form.description.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Fecha de apertura</label>
                <input type="datetime-local" id="open-time"
                class="form-control"
                name="open_time" v-model="form.open_time"
                min="2020-01-01T00:00" max="2021-12-31T00:00" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Fecha de clausura</label>
                <input type="datetime-local" id="close-time"
                class="form-control"
                name="close_time" v-model="form.close_time"
                min="2020-01-01T00:00" max="2021-12-31T00:00" required>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary ml-2 my-3" type="submit" :disabled="$v.$invalid" form="vote-form" >{{edit === '0' ? 'Registrar' : 'Actualizar'}}</button>
        </div>
    </div>
</template>

<script>
    import {validationMixin} from 'vuelidate'
    import {required, numeric, minValue, maxValue, maxLength, minLength, email, helpers} from 'vuelidate/lib/validators'
    const text = helpers.regex('alpha', /^[a-zA-Z0-9À-ÿ\u00f1\u00d1\s]*$/)
    export default {
        props:['edit', 'data'],
        data(){
            return{
                form:{
                    title:'',
                    description:'',
                    open_time:'',
                    close_time:''
                }
            }
        },
        validations:{
            form:{
                title:{
                    text
                },
                description:{
                    text
                }
            }
        },
        mounted() {
            if(this.data)
                this.form = {
                    ...JSON.parse(this.data)
                }
        }
    }
</script>
