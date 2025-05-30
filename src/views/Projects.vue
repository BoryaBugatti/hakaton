<script setup>
import { ref } from 'vue'
const orion = 'ORION'
const isBackDone = ref('');
const isFrontDone = ref('');
const isDevDone = ref('');
const isDbDone = ref('')
</script>
<template>
<div class="wrapper">
    <div class="projects-block">
            <div class="project">
                <div class="proj-field">
                    <h1 class = "proj-text-name">Название проекта</h1>
                    <h1 class = "proj-text-name">Имя проекта</h1>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Backend</p>
                    <select v-model="isBackDone" name="" id="">
                        <option value="ready">Сделан</option>
                        <option value="notready">Не сделан</option>
                    </select>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Frontend</p>
                    <select v-model="isFrontDone" name="" id="">
                        <option value="ready">Сделан</option>
                        <option value="notready">Не сделан</option>
                    </select>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Data Base</p>
                    <select v-model="isDbDone" name="" id="">
                        <option value="ready">Сделан</option>
                        <option value="notready">Не сделан</option>
                    </select>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-name">Dev</p>
                    <select v-model="isDevDone" name="" id="">
                        <option value="ready">Сделан</option>
                        <option value="notready">Не сделан</option>
                    </select>
                </div>
                <button class = "status-btn"type="button">Сохранить</button>
            </div>
        </div>
        </div>
</template>
<style scoped>
button{
    margin-top: 10px;

    background-color:  rgb(0, 135, 219);
    color: white;
    padding: 6px;
    border-radius: 16px;
    border: none;
}
.proj-text-name{
    font-size: 18px;
    color: rgb(32, 32, 32);
    font-size: 14px;
    font-family: "Noto Sans", sans-serif;
}
select{
    padding: 4px;
    width: 100px;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 16px;
    color: rgb(43, 43, 43);
    font-family: "Noto Sans", sans-serif;
    font-size: 14px;
}
button:hover{
    transition: 0.1s ease;
    color:  rgb(0, 135, 219);
    background-color: white;
    outline: 1px solid  rgb(0, 135, 219);
    cursor: pointer;
}
button:active{
    transform: scale(1.02);
}
.wrapper{
    margin-left: 2%;
    margin-right: 2%;
}
hr{
    border: 1px solid black;
    opacity: 0.1;
}
.proj-field {
    display: flex;
    justify-content: space-between;
    height: 35px;
    align-items: center;
}

.proj-label {
    font-weight: bold;
    color: #333;
}
.proj-text-progress{
    color: #199547;
    font-size: 18px;
}
.proj-text {
    color: #555555;
}
.project {
    background-color: #ffffff;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 8px;
    padding: 10px;
    max-width: 800px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-left: auto;
    margin-right: auto;
}
</style>