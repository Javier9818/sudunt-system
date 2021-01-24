<template>
    <div class="row">
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.nombres.text ? 'has-error' : ''} `">
                <label for="nombres">Nombres y apellidos</label>
                <input type="text" id="nombres" name="nombres" v-model="form.nombres" class="form-control" required>
                <p v-if="!$v.form.nombres.text" class="help text-danger">Este campo es inválido</p>
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
            <div :class="`form-group ${!$v.form.correo_personal.email ? 'has-error' : ''} `">
                <label for="correo_personal">Correo personal</label>
                <input type="email" id="correo_personal" name="correo_personal" v-model="form.correo_personal" class="form-control" >
                <p v-if="!$v.form.correo_personal.email" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div :class="`form-group ${!$v.form.correo_institucional.email ? 'has-error' : ''} `">
                <label for="correo_institucional">Correo institucional</label>
                <input type="email" id="correo_institucional" name="correo_institucional" v-model="form.correo_institucional" class="form-control" >
                <p v-if="!$v.form.correo_institucional.email" class="help text-danger">Este campo es inválido</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="facultad">Facultad</label>
                <input type="text" id="facultad"  name="facultad" v-model="form.facultad" class="form-control" >
                <!-- <p v-if="!$v.form.facultad.text" class="help text-danger">Este campo es inválido</p> -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="departamento">Departamento</label>
                <input type="text" id="departamento"  name="departamento" v-model="form.departamento" class="form-control" >
                <!-- <p v-if="!$v.form.departamento.text" class="help text-danger">Este campo es inválido</p> -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <input type="text" id="categoria"  name="categoria" v-model="form.categoria" class="form-control" >
                <!-- <p v-if="!$v.form.categoria.text" class="help text-danger">Este campo es inválido</p> -->
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select name="sexo" id="sexo" class="form-control" v-model="form.sexo" required>
                    <option :value="null" disabled selected>-- Porfavor, seleccione una opción --</option>
                    <option value="H">Masculino</option>
                    <option value="M">Femenino</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="tipo">Tipo</label>
                <p v-if="form.is_activo">Docente activo</p>
                <p v-else>Docente cesante</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Estado</label>
                <select name="status" id="status" v-model="form.status" class="form-control" disabled>
                    <option :value="1">Habilitado</option>
                    <option :value="0">Deshabilitado</option>
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
    export default {
        props:['edit', 'data'],
        data(){
            return{
                form:{
                    nombres:'',
                    code:'',
                    correo_institucional:'',
                    correo_personal:'',
                    departamento:'',
                    facultad:'',
                    categoria:'',
                    sexo:null,
                    is_activo: 0,
                    status:0

                }
            }
        },
        validations:{
            form:{
                nombres:{
                    text
                },
                code:{
                    text
                },
                correo_institucional:{
                    email
                },
                correo_personal:{
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
