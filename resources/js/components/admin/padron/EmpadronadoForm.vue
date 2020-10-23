<template>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group has-success">
                <label for="names">Nombres</label>
                <input type="text" id="names" name="names" v-model="form.names" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group has-success">
                <label for="last_names">Apellidos</label>
                <input type="text" id="last_names" name="last_names" v-model="form.last_names" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group has-success">
                <label for="code">Código</label>
                <input type="text" id="code"  name="code" v-model="form.code" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group has-success">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" v-model="form.email" class="form-control" required>
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary ml-2 my-3" type="submit">{{edit === '0' ? 'Registrar' : 'Actualizar'}}</button>
        </div>
    </div>
</template>

<script>
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
        mounted() {
            console.log('Component mounted.')
            if(this.data)
                this.form = {
                    ...JSON.parse(this.data)
                }
        }
    }
</script>
