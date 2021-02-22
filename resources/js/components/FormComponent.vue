<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            data: {
                type: Array,
                required: true,
            }
        },

        mounted() {
            for (let i = 0; i < this.data.length; i++) {
                for (let j = 0; j < this.data[i].length; j++) {
                    this.data[i][j] = this.data[i][j].replaceAll('/s', ' ')
                }
            }
            console.log(this.data)
        }
    }
</script>
