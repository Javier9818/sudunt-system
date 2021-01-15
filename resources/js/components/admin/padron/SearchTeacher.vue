<template>
    <div class="container">
        <div class="form-group">
            <label for="">Código de docente</label>
            <div class="row">
                <input type="text" placeholder="Ingrese su código de docente" class="form-control col-12 col-md-6" v-model="search">
                <button class="btn btn-success" @click="searchTeacher()">Buscar</button>
            </div>
        </div>
       <div class="row justify-content-center" v-if="load">
            <div class="lds-grid"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
       </div>
        <hr>
        <div class="alert alert-danger" role="alert" v-if="found == false">
            <h4 class="alert-heading">No se encontraron resultados para el código "{{search}}"</h4>
        </div>
        <div v-else-if="found === true">
            <div class="alert alert-danger" role="alert" v-if="(!$v.form.correo_personal.required || !$v.form.correo_personal.gmail) && (!$v.form.correo_institucional.required || !$v.form.correo_institucional.gmail)">
                <h4 class="alert-heading">Datos no válidos</h4>
                <p>Para realizar el acto de sufragio necesita que su correo electrónico personal o institucional sea del servicio Gmail.</p>
                <hr>
                <p class="mb-0">Para poder solicitar un cambio a sus datos, deberá descargar el siguiente <a href="/FORMATO DE ACTUALIZACION DE CORREO.docx" target="_blank">FORMATO DE ACTUALIZACIÓN DE CORREO</a>, firmar el documento, escanearlo y adjuntarlo en el siguiente formulario <a href="https://forms.gle/bzwQGggqmfo68vNN9" target="_blank">https://forms.gle/bzwQGggqmfo68vNN9</a></p>
            </div>

            <div class="alert alert-success" role="alert" v-else>
                <h4 class="alert-heading">Datos válidos</h4>
                <p>Podrá realizar el acto de sufragio con su correo {{ 
                    ($v.form.correo_institucional.gmail && $v.form.correo_personal.gmail && $v.form.correo_personal.required && $v.form.correo_institucional.required) ? 'institucional o correo personal' : 
                    ($v.form.correo_institucional.gmail && $v.form.correo_institucional.required) ? 'correo institucional' : 'correo personal gmail' }}.
                </p>
                <!-- <hr>
                <p class="mb-0"><b>(Opcional)</b> En caso desee cambiar los datos de su correo institucional o personal, deberá descargar el siguiente <a href="/FORMATO DE ACTUALIZACIÓN DE CORREO.docx" target="_blank">FORMATO DE ACTUALIZACIÓN DE CORREO</a>, firmar el documento, escanearlo y adjuntarlo en el siguiente formulario <a href="https://forms.gle/bzwQGggqmfo68vNN9" target="_blank">https://forms.gle/bzwQGggqmfo68vNN9</a>.</p> -->
            </div>

            <hr>
            <!-- {{!$v.form.correo_institucional.gmail}}
             {{!$v.form.correo_personal.gmail}}
             {{!$v.form.correo_institucional.required}}
             {{!$v.form.correo_personal.required}}
             {{(true || false)}}
             {{ (!$v.form.correo_personal.required || !$v.form.correo_personal.gmail) && (!$v.form.correo_institucional.required || !$v.form.correo_institucional.gmail)}} -->
            <div class="row box__teacher p-4">
                <div class="col-md-6">
                    <b>Nombres:</b>
                    <p>{{form.nombres || '-'}}</p>
                </div>
                <div class="col-md-6">
                    <b>Categoría:</b>
                    <p>{{form.categoria || '-'}}</p>
                </div>
                <!-- <div class="col-md-6">
                    <b>Facultad:</b>
                    <p>{{form.facultad || '-'}}</p>
                </div>
                <div class="col-md-6">
                    <b>Departamento:</b>
                    <p>{{form.departamento || '-'}}</p>
                </div> -->
                <!-- <div class="col-md-6">
                    <b>Sexo:</b>
                    <p>{{form.sexo || '-'}}</p>
                </div>
                <div class="col-md-6">
                    <b>Código:</b>
                    <p>{{form.code || '-'}}</p>
                </div> -->
                <div class="col-md-6">
                    <b>Correo institucional:</b>
                    <p>{{form.correo_institucional || '-'}}</p>
                </div>
                <div class="col-md-6">
                    <b>Correo personal:</b>
                    <p>{{form.correo_personal || '-'}}</p>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { helpers, required } from 'vuelidate/lib/validators'
    const gmail = helpers.regex('alpha', /^[\w-\.]+@((gmail.com)|(unitru.edu.pe))$/)

    export default {
        mounted() {
            this.$v.form.correo_institucional.$touch()
        },
        data(){
            return {
                search: '',
                found: null,
                load:false,
                form:{
                    nombres:'',
                    categoria:'',
                    facultad:'',
                    departamento:'',
                    sexo:'',
                    code:'',
                    correo_institucional:'',
                    correo_personal:''
                }
            }
        },
        validations:{
            form:{
                correo_institucional:{
                    gmail,
                    required
                },
                correo_personal:{
                    gmail,
                    required
                },
                
            }
        },
        methods:{
            searchTeacher: function(){
                let code = (this.search || '').trim()
                this.load = true
                axios.get(`/api/empadronado/${code}`).then( ({data}) => {
                   let { empadronado } = data

                    if(empadronado === null)
                        this.found = false
                    else{
                        this.found = true
                        this.form = {
                            nombres: empadronado.nombres,
                            categoria: empadronado.categoria,
                            facultad: empadronado.facultad,
                            departamento: empadronado.departamento,
                            sexo: empadronado.sexo,
                            code: empadronado.code,
                            correo_institucional: (empadronado.correo_institucional || '').trim(),
                            correo_personal: (empadronado.correo_personal || '').trim()
                        } 
                    }

                }).finally( () => {
                    this.load = false
                });
            }
        },
        watch:{
            search(){
                this.found = null
            }
        }

    }
</script>


<style scoped>
    .box__teacher{
        background: white;
        border-radius: 5px;
    }

    .lds-grid {
    display: inline-block;
    position: relative;
    width: 80px;
    height: 80px;
    }
    .lds-grid div {
    position: absolute;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: rgb(48, 68, 248);
    animation: lds-grid 1.2s linear infinite;
    }
    .lds-grid div:nth-child(1) {
    top: 8px;
    left: 8px;
    animation-delay: 0s;
    }
    .lds-grid div:nth-child(2) {
    top: 8px;
    left: 32px;
    animation-delay: -0.4s;
    }
    .lds-grid div:nth-child(3) {
    top: 8px;
    left: 56px;
    animation-delay: -0.8s;
    }
    .lds-grid div:nth-child(4) {
    top: 32px;
    left: 8px;
    animation-delay: -0.4s;
    }
    .lds-grid div:nth-child(5) {
    top: 32px;
    left: 32px;
    animation-delay: -0.8s;
    }
    .lds-grid div:nth-child(6) {
    top: 32px;
    left: 56px;
    animation-delay: -1.2s;
    }
    .lds-grid div:nth-child(7) {
    top: 56px;
    left: 8px;
    animation-delay: -0.8s;
    }
    .lds-grid div:nth-child(8) {
    top: 56px;
    left: 32px;
    animation-delay: -1.2s;
    }
    .lds-grid div:nth-child(9) {
    top: 56px;
    left: 56px;
    animation-delay: -1.6s;
    }
    @keyframes lds-grid {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
    }

</style>