<template>
    <div class="row">
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.names.text ? 'has-error' : ''} `">
                <label for="names">Nombres*</label>
                <input type="text" id="names" name="names" v-model="form.names" class="form-control" required>
                <p v-if="!$v.form.names.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.last_names.text ? 'has-error' : ''} `">
                <label for="last_names">Apellidos*</label>
                <input type="text" id="last_names" name="last_names" v-model="form.last_names" class="form-control" required>
                <p v-if="!$v.form.last_names.text" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.address.address ? 'has-error' : ''} `">
                <label for="address">Dirección</label>
                <input type="text" id="address"  name="address" v-model="form.address" class="form-control">
                <p v-if="!$v.form.address.address" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.phone.phone ? 'has-error' : ''} `">
                <label for="phone">Celular</label>
                <input type="text" id="phone"  name="phone" v-model="form.phone" class="form-control">
                <p v-if="!$v.form.phone.phone" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.dni.dni ? 'has-error' : ''} `">
                <label for="dni">Dni</label>
                <input type="text" id="dni"  name="dni" v-model="form.dni" class="form-control">
                <p v-if="!$v.form.dni.dni" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.email.email ? 'has-error' : ''} `">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" v-model="form.email" class="form-control" required>
                <p v-if="!$v.form.email.email" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Rol*</label>
                <select name="scope" id="scope" class="form-control" v-model="form.scope" required>
                    <option :value="null" disabled selected> -- Porfavor seleccione una opción --</option>
                    <option value="1">Administrador</option>
                    <option value="2">Personero</option>
                </select>
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
    const address = helpers.regex('alpha', /^[a-zA-Z0-9À-ÿ.#,\u00f1\u00d1\s]*$/)
    const phone = helpers.regex('alpha', /(\+[0-9]{2})?[0-9]{9}$/)
    const dni = helpers.regex('alpha', /[0-9]{8}$/)
    export default {
        props:['edit', 'user', 'person'],
        data(){
            return{
                form:{
                    names:'',
                    last_names:'',
                    address:'',
                    phone:'',
                    email:'',
                    scope:null,
                    dni:''
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
                address:{
                    address
                },
                phone:{
                    phone
                },
                email:{
                    email
                },
                dni:{
                    dni
                }
            }
        },
        mounted() {
            let {user, person} = this
            if(user){
                let d_user = JSON.parse(user),
                    d_person = JSON.parse(person)
                
                this.form = {
                    ...d_user,
                    ...d_person,
                    scope: d_user.is_admin === 1 ? 1 : 2
                }
            }
                
        }
    }
</script>
