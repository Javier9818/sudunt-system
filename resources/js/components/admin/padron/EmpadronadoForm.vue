<template>
    <div class="row">
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.names.text ? 'has-error' : ''} `">
                <label for="names">Nombres</label>
                <input type="text" id="names" name="names" v-model="form.names" class="form-control" required>
                <p v-if="!$v.form.names.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.last_names.text ? 'has-error' : ''} `">
                <label for="last_names">Apellidos</label>
                <input type="text" id="last_names" name="last_names" v-model="form.last_names" class="form-control" required>
                <p v-if="!$v.form.last_names.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.code.text ? 'has-error' : ''} `">
                <label for="code">Código</label>
                <input type="text" id="code"  name="code" v-model="form.code" class="form-control" required>
                <p v-if="!$v.form.code.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.email.email ? 'has-error' : ''} `">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" v-model="form.email" class="form-control" required>
                <p v-if="!$v.form.email.email" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary ml-2 my-3" type="submit">{{edit === '0' ? 'Registrar' : 'Actualizar'}}</button>
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
                    names:'',
                    last_names:'',
                    code:'',
                    email:''
                }
            }
        },
        validations:{
            form:{
                names:{
                    text
                },
                last_names:{
                    text
                },
                code:{
                    text
                },
                email:{
                    email
                }
            }
        },
        mounted() {
            console.log('Component mounted.')
            if(this.data)
                this.form = {
                    ...JSON.parse(this.data)
                }
        }
    }
</script>
