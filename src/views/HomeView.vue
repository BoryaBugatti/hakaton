<script setup>
import TheWelcome from '../components/TheWelcome.vue'



</script>

<template>
  <div class="wrapper">
  <div class="frame-wrapper">
    <img src="../../public/logo-files/frame.png" alt="" class="frame">
  </div>
  <main>
    <div class="left-info-side">
      <h1 class = "main-title">
        Управляйте Вашими 
        <span>IT проектами</span>
         Эффективно и Удобно!
      </h1>
      <p class = "main-page-info">Добро пожаловать в нашу платформу по менеджингу IT проектов! Мы предлагаем мощные инструменты и интуитивно понятный интерфейс для планирования, отслеживания и управления вашими проектами.</p>
      <p class = "gray-txt">В нашей системе вы найдете все необходимые инструменты для успешного управления IT проектами.</p>
      <h1 class ="main-title">Мы предлагаем:</h1>
      <div class="spans">    
        
      <span class = "span-text">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.77 5.82 22 7 14.14l-5-4.87 6.91-1.01z"></path>
          </svg>
          Мощные инструменты
          </span>
          <span class = "span-text">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.77 5.82 22 7 14.14l-5-4.87 6.91-1.01z"></path>
          </svg>
          Понятный интерфейс
          </span>
          <span class = "span-text">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87L18.18 22 12 18.77 5.82 22 7 14.14l-5-4.87 6.91-1.01z"></path>
          </svg>
          Индивидуальный подход
          </span>
          </div>
          <p class="main-page-info">Вы можете создать заявку прямо отсюда!</p>
      <div class="btns">
        <router-link to="/make-app">Создайте заявку!</router-link>
      </div>
    </div>
    <div class="right-info-side">
      <img class = "main-page-img"src="../../public/logo-files/programming.png" alt="">
    </div>
  </main>
  <div class="stack-wrapper">
  <div class="bubble-container">
    <div class="bubble"><img src="../../public/js.jpg" alt=""></div>
    <div class="bubble"><img src="../../public/html.jpg" alt=""></div>
    <div class="bubble"><img src="../../public/php.jpg" alt=""></div>
    <div class="bubble"><img src="../../public/java.jpg" alt=""></div>
  </div>
  <div class="stack-info">
    <h1 class="main-title">Наш стек</h1>
    <div class="tech-grid">
            <div class="tech-card">
                <h3 class="tech-category">🌐 Frontend</h3>
                <ul class="tech-list">
                    <li class="tech-item">
                        <span class="tech-icon">🟢</span> HTML5, CSS3, JavaScript
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">⚛️</span> React/Vue.js
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🎨</span> SASS/SCSS
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">✨</span> GSAP/Framer Motion
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">📊</span> Chart.js/D3.js
                    </li>
                </ul>
            </div>
            <div class="tech-card">
                <h3 class="tech-category">⚙️ Backend</h3>
                <ul class="tech-list">
                    <li class="tech-item">
                        <span class="tech-icon">🟨</span> Node.js (Express/NestJS)
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🐍</span> Python (Django/FastAPI)
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🗄️</span> PostgreSQL/MySQL
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🔥</span> MongoDB/Firebase
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🚀</span> Redis
                    </li>
                </ul>
            </div>
            <div class="tech-card">
                <h3 class="tech-category">🛠 DevOps</h3>
                <ul class="tech-list">
                    <li class="tech-item">
                        <span class="tech-icon">🐳</span> Docker/Kubernetes
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">☁️</span> AWS/Google Cloud
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🔁</span> CI/CD (GitHub Actions)
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🔒</span> JWT/OAuth 2.0
                    </li>
                    <li class="tech-item">
                        <span class="tech-icon">🛡️</span> HTTPS, CSP
                    </li>
                </ul>
            </div>
        </div>
  </div>
  </div>
  </div>
</template>
<style scoped>
.stack-wrapper{
  display: grid;
  grid-template-columns: 400px auto;
}
.bubble-container {
  z-index: -7;
  position: relative;
  width: 400px;
  height: 200px; 
  border-radius: 12px;
  margin-left: -50px;
  margin-top: 100px; 
}
.bubble {
  position: absolute;
  width: 60px;
  height: 60px;
  border-radius: 50%; 
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden; 
}
.tech-section {
  max-width: 1200px;
  margin: 0 auto;
  padding: 4rem 2rem;
}
        
.section-title {
  text-align: center;
  font-size: 2.5rem;
  margin-bottom: 3rem;
  color: var(--primary);
}
.tech-grid {
  margin-right: 50px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
  gap: 1rem;
}
        
.tech-card {
  font-family: "Noto Sans", sans-serif;
  background: white;
  border-radius: 10px;
  padding: 2rem;
  transition: transform 0.3s ease;
}
        
.tech-card:hover {
  
  transform: translateY(-10px);
}     
.tech-category {
  font-family: "Noto Sans", sans-serif;
  font-size: 1.5rem;
  color: var(--secondary);
  margin-bottom: 1.5rem;
  padding-bottom: 0.5rem;
  border-bottom: 2px solid #eee;
}
        
.tech-list {
  font-family: "Noto Sans", sans-serif;
  list-style-type: none;
  padding-left: 0;
}
        
.tech-item {
  font-family: "Noto Sans", sans-serif;
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
}
        
.tech-icon {
  width: 24px;
  height: 24px;
  margin-right: 10px;
  color: var(--primary);
  background-color: white;
}
@media (max-width: 768px) {
.tech-grid {
  grid-template-columns: 1fr;
  }
            
.section-title {
  font-size: 2rem;
}
}
.bubble img {
  width: 100%; 
  height: 100%;
  object-fit: cover; 
  object-position: center; 
}

@keyframes float {
  0%, 100% {
    transform: translate(0, 0);
  }
  25% {
    transform: translate(30px, -30px);
  }
  50% {
    transform: translate(20px, -60px);
  }
  75% {
    transform: translate(-30px, -30px);
  }
}

.bubble:nth-child(1) {
  top: 10%;
  left: 40%;
  animation: float 6s infinite ease-in-out;
}
.bubble:nth-child(4) {
  top: 90%;
  left: 80%;
  animation: float 6s infinite ease-in-out;
}

.bubble:nth-child(2) {
  top: 50%;
  left: 60%;
  animation: float 9s infinite ease-in-out reverse;
}

.bubble:nth-child(3) {
  top: 70%;
  left: 40%;
  animation: float 8s infinite ease-in-out;
}
.spans{
  display: flex;
  gap: 10px;
  align-items: center;
}
.span-text{

  background-color: rgb(0, 135, 219);;
  display: flex;
  gap: 4px;
  padding-right: 8px;
  align-items: center;
  font-family: "Noto Sans", sans-serif;
}
.info-block-txt{
  max-width: 600px;
}
.info-block-sec{
  display: grid;
  grid-template-columns: auto auto;
}
.sec-img{
  margin-left: 100px;
  width: 400px;
  height: auto;
}

.frame{
  transition: rotate(180deg);
  width: 98vw;
  object-fit: cover;
  height: 100vh;
}
.gray-txt{
  text-decoration: none;
  color: rgb(166, 166, 166);
  padding:6px;
  font-family: "Noto Sans", sans-serif;
  border-radius: 24px;
  font-size: 12px;
}
hr{
  border: 1px solid black;
  opacity: 0.1;
}
.frame{
  position: absolute;
  top: 0;
  left: 0;
  z-index: -10;
}
.btns{
  margin-top: 30px;
}
.main-page-info{
  font-family: "Noto Sans", sans-serif;
  font-weight: 500;
  color: rgb(43, 43, 43);
}
.main-page-img{
  height: auto;
  max-width: 100%;
}
a{
  background-color: rgb(255, 255, 255);
  text-decoration: none;
  color: rgb(0, 135, 219);
  padding:6px;
  outline: 1px solid rgb(0, 135, 219);
  font-family: "Noto Sans", sans-serif;
  border-radius: 24px;
}
a:hover{

  color: rgb(255, 255, 255);
  background-color: rgb(0, 135, 219);;
  transition: 0.1s ease;
}
span{
  background-color: rgb(111, 200, 255);
  color: white;
  padding: 4px;
  border-radius: 16px;
}
.left-info-side{
   height: auto;
  max-width: 100%;
}
.right-info-side{
  display: flex;
  align-items: flex-start;
}
.main-title{
  
  font-family: "Noto Sans", sans-serif;
  font-weight: 800;
  color: rgb(43, 43, 43);
}
main{
  margin-left: auto;
  margin-right: auto;
  width: 90vw;
  display: grid;
  grid-template-columns: auto auto;
}
@media screen and (max-width: 480px){
  .bubble-container{
    display: none;
  }
  .main-title{
    font-family: "Noto Sans", sans-serif;
    font-weight: 800;
    color: rgb(43, 43, 43);
    font-size: 20px;
  }
  main{
    display: flex;
    flex-direction: column;
    margin-left: auto;
    margin-right: auto;
    width: 95vw;
  }
  span{
    background-color: white;
    color: rgb(43, 43, 43)
  }
  .spans{
    margin-top: 10px;
    margin-bottom: 10px;
  }
  .span-text{
  background-color: rgb(0, 135, 219);;
  display: flex;
  color: white;
  font-size: 10px;
  gap: 4px;
  padding-right: 8px;
  align-items: center;
  font-family: "Noto Sans", sans-serif;
}
.stack-wrapper{
  display: flex;
  flex-direction: column;

}
}
</style>
