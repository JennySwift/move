<template>
    <div class="sheet-modal keypad" :id="id">
        <div class="toolbar">
            <div class="toolbar-inner">
                <div class="left">{{value}}</div>
                <div class="right">
                    <a href="#" class="link sheet-close">Done</a>
                </div>
            </div>
        </div>
        <div class="sheet-modal-inner">
            <div class="keypad-row">
                <button v-on:click="buttonClicked('1')">1</button>
                <button v-on:click="buttonClicked('2')">2</button>
                <button v-on:click="buttonClicked('3')">3</button>
            </div>
            <div class="keypad-row">
                <button v-on:click="buttonClicked('4')">4</button>
                <button v-on:click="buttonClicked('5')">5</button>
                <button v-on:click="buttonClicked('6')">6</button>
            </div>
            <div class="keypad-row">
                <button v-on:click="buttonClicked('7')">7</button>
                <button v-on:click="buttonClicked('8')">8</button>
                <button v-on:click="buttonClicked('9')">9</button>
            </div>
            <div class="keypad-row">
                <button v-on:click="buttonClicked('clear')">Clear</button>
                <button v-on:click="buttonClicked('0')">0</button>
                <button v-on:click="buttonClicked('delete')"><i class="fas fa-arrow-alt-circle-left"></i></button>
            </div>

        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                shared: store.state,
                mutableValue: this.value
            }
        },
        methods: {
            buttonClicked: function (value) {
                if (value === 'clear') {
                    this.mutableValue = '';
                }
                else if (value === 'delete') {
                    this.mutableValue = this.mutableValue.toString().slice(0, -1);
                }
                else {
                    this.mutableValue+= value;
                }

                this.$emit('update:value', this.mutableValue);
            }
        },
        props: [
            'value',
            'id'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    .keypad {
        //Stretch buttons to fit vertically
        .sheet-modal-inner {
            display: flex;
            flex-direction: column;
            .keypad-row {
                flex-grow: 1;
                display: flex;
                border-bottom: 1px solid #ccc;
                &:last-child {
                    border-bottom: none;
                }
                //Stretch buttons to fit horizontally
                button {
                    flex-grow: 1;
                    border-radius: 0;
                    padding: 12px 0;
                    font-size: 20px;
                    background: white;
                    border: none;
                    border-right: 1px solid #ccc;
                    margin: 0;
                    &:last-child {
                        border-right: none;
                    }
                }
            }
        }

    }
</style>