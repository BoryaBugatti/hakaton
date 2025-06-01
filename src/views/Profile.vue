<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { computed } from 'vue';
import axios from 'axios';
const orion =ref('ORION')
const user = ref({});
const router = useRouter();
const route = useRoute();
const projects = ref([]);
const isOwner = ref(false);
const showed = ref(false);
const currentProject = ref(null);
const milestones = ref([]);

const filterSelect = ref('name');
const filteredProjects = computed(() => {
  if (!projects.value) return [];
  
  const projectsCopy = [...projects.value];
  
  switch(filterSelect.value) {
    case 'name':
      return projectsCopy.sort((a, b) => a.project_name.localeCompare(b.project_name));
    case 'date':
      return projectsCopy.sort((a, b) => new Date(b.project_date) - new Date(a.project_date));
    default:
      return projectsCopy;
  }
});
const calculateOverallProgress = () => {
  if (!milestones.value || !milestones.value.length) return 0;
  
  const completed = milestones.value.filter(
    m => m.status === 'completed' || m.status === 'завершено' || m.status === 'готово'
  ).length;
  
  return Math.round((completed / milestones.value.length) * 100);
};
const getPieSegmentPath = (percentage, index) => {
  if (percentage === 0) return '';
  
  const total = getStatusDistribution().reduce((sum, item) => sum + item.percentage, 0);
  const offset = getStatusDistribution().slice(0, index).reduce((sum, item) => sum + item.percentage, 0);
  
  const startAngle = (offset / total) * 360;
  const endAngle = ((offset + percentage) / total) * 360;
  
  const startRad = (startAngle - 90) * Math.PI / 180;
  const endRad = (endAngle - 90) * Math.PI / 180;
  
  const x1 = 50 + 40 * Math.cos(startRad);
  const y1 = 50 + 40 * Math.sin(startRad);
  const x2 = 50 + 40 * Math.cos(endRad);
  const y2 = 50 + 40 * Math.sin(endRad);
  
  const largeArcFlag = endAngle - startAngle <= 180 ? 0 : 1;
  
  return `M50,50 L${x1},${y1} A40,40 0 ${largeArcFlag},1 ${x2},${y2} Z`;
};


const formatDate = (dateString) => {
  if (!dateString) return 'Дата не указана';
  const options = { day: 'numeric', month: 'long', year: 'numeric' };
  return new Date(dateString).toLocaleDateString('ru-RU', options);
};
const ShowStatus = (project_id) => {
  currentProject.value = projects.value.find(p => p.project_id === project_id);
  fetchMilestones(project_id);
  showed.value = true;
};
const fetchMilestones = async (projectId) => {
  try {
    const response = await axios.get(`http://localhost:8090/api/milestones/${projectId}`);
    milestones.value = response.data;
  } catch (error) {
    console.error("Ошибка при получении вех проекта!", error);
  }
};
function logout() {
  localStorage.removeItem('user'); 
  router.push('/login'); 
}
const closeModal = () => {
  showed.value = false;
};
const fetchUserData = async (clientId) => {
  try {
    const response = await axios.get(`http://localhost:8090/api/user/${clientId}`);
    const data = response.data;
    user.value = data.client;
    projects.value = data.projects;
    const storedUser = JSON.parse(localStorage.getItem('user'));
    console.log('Stored user:', storedUser);
    if (storedUser && storedUser.client_id.toString() === clientId.toString()) {
      isOwner.value = true;
    } else {
      isOwner.value = false; 
    }

    console.log('Is owner:', isOwner.value);
    console.log('User data:', user.value);
  } catch (error) {
    console.error("Ошибка при получении данных пользователя!", error);
    router.push({ name: 'login' });
  }
}


onMounted(() => {
  const clientId = route.params.client_id;
  if (clientId) {
    fetchUserData(clientId);
  } else {
    console.error("client_id не найден в параметрах маршрута");
  }
});
watch(() => route.params.client_id, (newClientId) => {
  if (newClientId) {
    fetchUserData(newClientId);
  }
});

const getStatusDistribution = () => {
  const statusCounts = {
    completed: 0,
    in_progress: 0,
    not_started: 0
  };

  milestones.value.forEach(milestone => {
    if (milestone.status === 'completed' || milestone.status === 'готово') {
      statusCounts.completed++;
    } else if (milestone.status === 'in_progress' || milestone.status === 'в работе') {
      statusCounts.in_progress++;
    } else {
      statusCounts.not_started++;
    }
  });

  return Object.entries(statusCounts).map(([status, count]) => ({
    status,
    count,
    percentage: milestones.value.length 
      ? Math.round((count / milestones.value.length) * 100)
      : 0
  }));
};

const getStatusText = (status) => {
  const statusMap = {
    completed: 'Завершено',
    'готово': 'Завершено',
    in_progress: 'В работе',
    'в работе': 'В работе',
    not_started: 'Не начато',
    'не начато': 'Не начато'
  };
  return statusMap[status] || status;
};

const getStatusColor = (status) => {
  const colors = {
    completed: '#4CAF50',
    'готово': '#4CAF50',
    in_progress: '#FFC107',
    'в работе': '#FFC107',
    not_started: '#9E9E9E',
    'не начато': '#9E9E9E'
  };
  return colors[status] || '#9E9E9E';
};
</script>
<template>
<div class="profile-wrapper">
    <div class="frame-wrapper">
        <img src="../../public/logo-files/frame.png" alt="" class="frame">
    </div>
    <div class="wrapper">
        <h1>Добро пожаловать, {{ user.user_name }}!</h1>
        <div class="user-info-block">
            <div class="user-avatar">
            <img class = "avatar" src="https://rossoshanskij-r20.gosweb.gosuslugi.ru/netcat_files/9/260/2186_logo.jpg" alt="">
        </div>
            <div class="info">
                <div class="email-block">
                    <h3>Ваш E-mal:</h3>
                    <p class = "email">{{ user.user_email }}</p>
                </div>
                <div class="btns">
                <button type="button" @click="logout">Выйти</button>
                
                    <router-link v-if="user.user_role == 'менеджер'" to="/confirm-apps">Заявки</router-link>
                    <router-link v-if="user.user_role == 'менеджер'" to="/projects-tracking">Проекты</router-link>
                </div>
                
            </div>
            
        </div>
        
        <h1> Ваши проекты в разработке:</h1>
        <p class="under-txt">Ниже представлены все ваши проекты, которые вы можете отфильтровать</p>
        <select v-model="filterSelect">
          <option value="name">По названию</option>
          <option value="date">По дате</option>
        </select>
        <p v-if="!projects"class="under-txt">У вас пока нет активных проектов</p>
        <div v-else v-for = "project in projects" :key="project.project_name"class="projects-block">
          
            <div class="project">
                <div class="proj-field">
                    <p class = "proj-text-name">Название проекта</p>
                    <p class = "proj-text-name">Проект {{ project.project_name }}</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Тип проекта</p>
                    <p class = "proj-text">{{ project.project_stack }}</p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Времени выделено</p>
                    <p class = "proj-text"> до {{ project.project_time }} </p>
                </div>
                <hr>
                <div class="proj-field">
                    <p class = "proj-text-title">Прогресс</p>
                    <button @click ="ShowStatus(project.project_id)"class = "status-btn" type="button">Состояние</button>
                </div>
                
            </div>
             <div v-if="showed" class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <button class="close-btn" @click="closeModal">×</button>
      
      <h1>Состояние проекта: {{ currentProject?.project_name }}</h1>
      
      <div class="progress-section">
        <h3>Общий прогресс</h3>
        <div class="progress-container">
          <div 
            class="progress-bar" 
            :style="{ width: calculateOverallProgress() + '%' }  "
          ></div>
        </div>
        <span class="progress-text">{{ calculateOverallProgress() }}%</span>
      </div>
      
      <div class="milestones-section">
        <h3>Вехи проекта</h3>
        <div class="timeline">
          <div 
            v-for="milestone in milestones" 
            :key="milestone.name" 
            class="milestone-item"
            :class="{
              'completed': milestone.status === 'готово',
            }"
          >
            <div class="milestone-dot"></div>
            <div class="milestone-info">
              <h4>{{ milestone.name }}</h4>
              <p>Обновлено: {{ formatDate(milestone.updateDate) }}</p>
              <p>Статус: {{ getStatusText(milestone.status) }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="chart-section">
  <h3>Распределение статусов</h3>
  <div class="pie-chart-container">
    <svg class="pie-chart" viewBox="0 0 100 100">
      <circle cx="50" cy="50" r="40" fill="#f5f5f5" />
      <path 
        v-for="(status, index) in getStatusDistribution()" 
        :key="index"
        :d="getPieSegmentPath(status.percentage, index)"
        :fill="getStatusColor(status.status)"
      />
      <circle cx="50" cy="50" r="25" fill="white" />
      <text x="50" y="52" text-anchor="middle" font-size="12" fill="#333">
        {{ calculateOverallProgress() }}%
      </text>
    </svg>
  </div>
  <div class="chart-legend">
    <div v-for="status in getStatusDistribution()" :key="status.status" class="legend-item">
      <span class="legend-color" :style="{ backgroundColor: getStatusColor(status.status) }"></span>
      <span class="legend-text">{{ getStatusText(status.status) }}: {{ status.count }} ({{ status.percentage }}%)</span>
    </div>
  </div>
</div>
    </div>
  </div>
        </div>
        <div class="stack">
          
        </div>
    </div>
</div>
</template>
<style scoped>
.chart-section {
  margin: 2rem 0;
  text-align: center;
}
select{
    margin-left: 30%;
    width: 150px;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 16px;
    color: rgb(43, 43, 43);
    font-family: "Noto Sans", sans-serif;
    font-size: 16px;
}
.pie-chart-container {
  width: 200px;
  height: 200px;
  margin: 0 auto;
}
.pie-chart text{
    font-family: "Noto Sans", sans-serif;
}
.pie-chart {
  width: 100%;
  height: 100%;
}

.chart-legend {
  display: flex;
  flex-direction: column;
  gap: 0.8rem;
  margin-top: 1.5rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.8rem;
}

.legend-color {
  width: 16px;
  height: 16px;
  border-radius: 3px;
  flex-shrink: 0;
}

.legend-text {
  font-family: "Noto Sans", sans-serif;
  font-size: 0.9rem;
  color: #444;
}
.btns{
    display: flex;
    gap: 8px;
    margin-top: 10px;
    align-items: center;
}
.project-title {
    font-size: 1.5em;
}
a{
    margin-top: 11px;
  background-color: rgb(0, 135, 219);
  text-decoration: none;
  color: white;
  padding:4px;
  font-family: "Noto Sans", sans-serif;
  border-radius: 24px;
  font-size: 14px;
}
a:hover{
  outline: 1px solid rgb(0, 135, 219);
  color: rgb(0, 135, 219);
  background-color: white;
  transition: 0.1s ease;
}
.status-btn:hover{
  cursor: pointer;
  background-color: rgb(0, 135, 219);
  
  outline: 1px solid white;
  color: white;
  transition: 0.1s ease;
}

.status-btn:active{
  transform: scale(0.95);
}
.status-btn{
  margin-bottom: 10px;
  padding: 6px;
  border-radius: 16px;
  border: none;
  background-color: #ffffff;
  color: rgb(0, 135, 219);
  font-family: "Noto Sans", sans-serif;
  font-size: 16px;
  font-weight: 500;
  margin-top: 20px;
  outline: 1px solid rgb(0, 135, 219);
  }
.proj-text-name{
    font-size: 18px;
    color: rgb(0, 135, 219);
    font-size: 20px;
}
.frame{
  position: absolute;
  top:50;
  left:0;
  transition: rotate(180deg);
  width: 98vw;
  object-fit: cover;
  height: 100vh;
  z-index: -10;
}
.under-txt{
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
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
  margin-top: 10px;
    background-color: #ffffff;
    border: 1px solid rgb(0, 135, 219);
    border-radius: 8px;
    padding: 10px;
    max-width: 800px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-left: auto;
    margin-right: auto;
}

.email-block{
    margin-top: 15px;
    display: flex;
    align-items: center;
    height: 20px;
}
button{
    margin-top: 10px;

    background-color: #ff6e6e;
    color: white;
    padding: 6px;
    border-radius: 16px;
    border: none;
}
button:hover{
    transition: 0.1s ease;
    color: #ff5a5a;
    background-color: white;
    outline: 1px solid #ff5a5a;
    cursor: pointer;
}
button:active{
    transform: scale(1.02);
}
.profile-wrapper{
    display: flex;
    flex-direction: column;
}
.avatar{
    width: 80px;
    border-radius: 50px;
    border: 2px solid rgb(0, 135, 219);
}
h1{
   max-width: 800px;
    margin-left: auto;
  font-family: "Noto Sans", sans-serif;
  font-weight: 700;
  font-size: 24px;
  margin-right: auto;
  color: rgb(43, 43, 43);
}
.email{
    margin-left: 10px;
}
.info{
    height: 30px;
}
h3{
    font-family: "Noto Sans", sans-serif;
  font-weight: 700;
  font-size: 16px;
  color: rgb(43, 43, 43);
}
p{
    font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  font-size: 14px;
  color: rgb(43, 43, 43);
}
.user-info-block{
    display: flex;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
    gap: 20px;
}
.wrapper{
    margin-left: 2%;
    margin-right: 2%;
}
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 2rem;
  border-radius: 10px;
  max-width: 800px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
}

.close-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  font-size: 1.5rem;
  background: none;
  border: none;
  cursor: pointer;
  color: #555;
}
.progress-section {
  margin: 1.5rem 0;
}
.progress-container text{
    font-family: "Noto Sans", sans-serif;
}
.progress-container {
  width: 100%;
  height: 20px;
  background-color: #f0f0f0;
  border-radius: 10px;
  margin-top: 0.5rem;
  overflow: hidden;
  display: inline-block;
}
.progress-bar text{
    font-family: "Noto Sans", sans-serif;
}
.progress-text {
  font-family: "Noto Sans", sans-serif;
}
.progress-bar {
  
  height: 100%;
  background-color: #0087db;
  transition: width 0.5s ease;
}

.progress-text {
  margin-left: 1rem;
  font-weight: bold;
  vertical-align: top;
}

.timeline {
  margin: 2rem 0;
  position: relative;
  padding-left: 30px;
}

.timeline::before {
  content: '';
  position: absolute;
  left: 10px;
  top: 0;
  bottom: 0;
  width: 2px;
  background-color: #e0e0e0;
}

.milestone-item {
  position: relative;
  margin-bottom: 1.5rem;
  padding-left: 20px;
}

.milestone-dot {
  position: absolute;
  left: 0;
  top: 5px;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 3px solid #fff;
  box-shadow: 0 0 0 2px #0087db;
}

.milestone-item.completed .milestone-dot {
  background-color: #4caf50;
  box-shadow: 0 0 0 2px #4caf50;
}

.milestone-item.in-progress .milestone-dot {
  background-color: #ffc107;
  box-shadow: 0 0 0 2px #ffc107;
}

.milestone-item.not-started .milestone-dot {
  background-color: #f44336;
  box-shadow: 0 0 0 2px #f44336;
}

.milestone-info {
  background: #f9f9f9;
  padding: 1rem;
  border-radius: 5px;
}

.milestones-section{
  font-family: "Noto Sans", sans-serif;
}

.pie-chart {
  width: 200px;
  height: 200px;
  border-radius: 50%;
  position: relative;
  margin: 1rem auto;
  background: #ffffff;
}

.pie-segment {
  position: absolute;
  width: 100%;
  height: 100%;
  clip-path: polygon(50% 50%, 50% 0, 100% 0, 100% 100%, 50% 100%);
  background-color: var(--color);
  transform: rotate(calc(var(--offset) * 3.6deg)) 
             skewY(calc((var(--percentage) * -3.6deg) + 90deg));
}

.pie-center {
  position: absolute;
  width: 70%;
  height: 70%;
  background: white;
  border-radius: 50%;
  top: 15%;
  left: 15%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  font-size: 1.5rem;
}

.chart-legend {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 1rem;
  margin-top: 1rem;
}

.legend-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.legend-color {
  width: 15px;
  height: 15px;
  border-radius: 3px;
}
@media screen and (max-width: 480px){
    .frame{
        display: none;
    }
    .projects{
        display: flex;
        flex-direction: column;
        max-width: 400px;
    }
}
</style>