<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group" v-for="(item, index) in modifiedData[0]" :key="index">
                            <div v-if="optionValue(index).length > 0 && showSelect(index)">
                                <label>{{item}}</label>
                                <select class="form-control" @change="(event) => selectedOption(event, index)" :disabled="disabledSelect(index)">
                                        <option :value=0 selected>Choose option</option>
                                        <option  v-for="(element, ind) in optionValue(index)" :key="ind" :hidden="element==null" :value="ind">{{element}}</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary" @click="restart">Restart</button>
                        <button type="button" class="btn btn-secondary" :hidden="hideDownloadButton" @click="download">Download PDF</button>
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
        data: function () {
            let data = this.data
            for (let i = 0; i < data.length; i++) {
                for (let j = 0; j < data[i].length; j++) {
                    this.data[i][j] = data[i][j].replaceAll('/s', ' ')
                }
                data[i] = Object.assign({}, data[i])
            }
            data = Object.assign({}, data)

            let index = [];

            for (const element in this.data) {
                index[element] = false;
            }

            return {
                currentSelect: 0,
                selected: [],
                modifiedData: data,
                indentificator: index,
                hideDownloadButton: true,
            }
        },
        methods: {
            download: function () {
                axios.get('/download')
                .then((response) => {
                    let url = response.data.file

                    window.open(url, '_blank')
                })
            },
            restart: function () {
                this.selected = [];

                let index = [];

                for (const element in this.data) {
                    index[element] = false;
                }    

                this.indentificator = index;
                this.hideDownloadButton = true;
                this.currentSelect = 0;
                this.$forceUpdate()
            },
            optionValue: function (index) {
                let array = []

                for(const ind in this.modifiedData) {
                    const hide = this.selected.includes(ind);
                    if (this.indentificator[index] || (!hide && this.modifiedData[ind] !== null && this.modifiedData[ind][index] != "" && ind > 0)) {
                        array[ind] = this.modifiedData[ind][index]
                    }
                }

                if (this.indentificator[index] && (index == this.currentSelect)) {
                    this.currentSelect++;
                }
                if (array.length === 0) {
                    this.indentificator[index] = true;
                }
                return array
            },
            disabledSelect : function (index) {
                return this.indentificator[index]
            },
            showSelect: function (index) {   
                let value = this.indentificator.indexOf(false)

                if (index < this.currentSelect && this.selected[index] == undefined) {
                    return false
                }

                if (value < 0) {
                    this.hideDownloadButton = false

                    if (this.selected[index] != undefined) {
                        return true
                    }   else {
                        return false
                    }
                }
                
                return (value >= index)
            },
            selectedOption: function (event, index) {
                if (event.target.value != 0) {
                    this.indentificator[index] = true
                    this.selected[index] = event.target.value
                    this.currentSelect++;
                    this.$forceUpdate()
                }
            }
        }
    }
</script>
