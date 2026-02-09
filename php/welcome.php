<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php"); // redirect back to login if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/css/img/letter-s.png" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Welcome - Seek Jobs Ghana</title>

  <style>
    :root {
      --primary-green: #77e64c;
      --secondary-green: #58c853;
      --bg1: #c4fac4;
      --bg2: #bbf3bb;
      --card: #ffffff99;
      --muted: #1a1a1a;
      --accent: #77e64c;
      --accent-2: #71db6b;
      --toast: #66f12f;
      --dark-bg: #1c1a1a;
      --light-bg: #e3e3e3;
      --white: #ffffff;
      --hover: #71f06a;
      --text-color: #235347;
      --text-dark: #333;
      --text-light: #ffffff;
      --radius: 14px;
      --glass-border: rgba(255, 255, 255, 0.6);
      --shadow: 0 12px 40px rgba(8, 30, 80, 0.08);
      --toast-bg: rgba(15, 23, 42, 0.95);
    }

    * {
      font-family: 'Montserrat', 'Poppins', Arial, sans-serif;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      margin: 0;
      background: linear-gradient(180deg, var(--bg1) 0%, var(--bg2) 100%);
      color: #0f172a;
      scroll-behavior: smooth;
    }

    header {
      background: #f1f6f2;
      border-bottom: 1px solid #d4e7d6;
      position: sticky;
      top: 0;
      z-index: 99999;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: auto;
    }

    nav {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1rem 0;
    }

    .logo a {
      font-size: 2rem;
      font-weight: 700;
      text-decoration: none;
      color: var(--text-color);
    }

    .nav {
      display: flex;
      gap: 2rem;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav li a {
      color: #333;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s;
    }

    .nav li a:hover,
    .nav li a.login {
      color: var(--secondary-green);
    }

    .ham-menu {
      display: none;
      font-size: 2rem;
      cursor: pointer;
    }


    h1 {
      font-size: 18px;
      margin: 0;
    }

    .hero {
  background: var(--card);
  backdrop-filter: blur(6px);
  padding: 16px;
  box-shadow: var(--shadow);
  border-radius: 14px;
  border: 1px solid var(--glass-border);
       margin: 10px 10px 0 10px;
       display: flex;
       flex-direction: row;
       gap: 10px;
    }

    .dashboard-grid {
       padding: 15px;
       border: 1px solid #111;
       display: flex;
       flex-direction: row;
       background: var(--white);
       backdrop-filter: blur(8px);
       /*box-shadow: var(--shadow);*/
       border-radius: var(--radius);
       border: 1px solid var(--glass-border);
       transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    /*.dashboard-grid:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 60px rgba(8, 80, 32, 0.12);
    }*/

    .profile-card {
      max-width: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
    }

    .profile-pic {
      width: 88px;
      height: 88px;
      border-radius: 50%;
      overflow: hidden;
       background: linear-gradient(135deg, #f3f8f3, #f1fff1);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 14px;
      color: var(--text-dark);
      transition: transform 0.18s ease;
    }

    .profile-pic img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .profile-name {
      font-weight: 800;
      font-size: 16px;
    }

    .profile-bio {
      color: var(--muted);
      font-size: 13px;
      text-align: center;
    }

    .profile-actions {
      display: flex;
      gap: 8px;
    }

/* Stats Section */
.stats-wrap {
  grid-column: span 9;
  display: flex;
  gap: 12px;
  align-items: stretch;
}

.stat {
  width: 300px;
  padding: 14px;
  display: flex;
  flex-direction: column;
  gap: 6px;
}


.stat .label {
  color: var(--muted);
  font-size: 14px;
  font-weight: 400;
}

.stat .value {
  color: #0e4613;
  font-size: 20px;
  font-weight: 900;
}

.stat .small {
  font-size: 12px;
  color: var(--muted);
}

.welcome {
    padding: 20px;
    background: var(--white);
    backdrop-filter: blur(8px);
    border-radius: var(--radius);
    border: 1px solid var(--glass-border);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
    margin: 5px;
    max-width: 650px;
}

    /*.welcome:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 60px rgba(8, 80, 32, 0.12);
    }*/

    .motto {
        font-weight: 600;
        font-size: 13px;
        font-style: italic;
    }

.welcome h1 {
    font-size: 1.5rem;
}

    /* Buttons */
    .btn {
      padding: 8px 12px;
      border-radius: 20px;
      border: 0;
      background: transparent;
      cursor: pointer;
      font-weight: 700;
      transition: color 0.2s, background 0.2s;
    }

    .btn.primary {
      background: var(--accent);
      color: white;
      transition: background 0.2s;
    }

    .btn.primary:hover {
      background: #2fc727;
    }

    .btn.ghost {
      border: 1px solid #eef2f7;
      background: white;
    }

    .btn.ghost:hover {
      background: #f6f6f6;
    }

    .btn.cancel:hover {
      color: #0e4613;
    }

    .btn.danger {
      background: #e74c3c;
      color: white;
    }

    .btn.danger:hover {
      background: #c0392b;
    }

    .btn.apply {
      color: #494949;
      transition: background 0.2s, color 0.2s;
    }

    .btn.apply:hover {
      color: #fff;
      background: var(--accent);
    }

    .btn.save {
      color: #494949;
      transition: background 0.2s, color 0.2s;
    }

    .btn.save:hover {
      color: #fff;
      background: #58c853;
    }

    .btn.connect {
      color: var(--text-light);
      background: var(--primary-green);
      padding: 8px 12px;
      border-radius: 20px;
      border: 0;
      cursor: pointer;
      font-weight: 700;
      transition: color 0.2s, background 0.2s;
    }

    .btn.connect:hover {
      background: var(--secondary-green);
      color: var(--text-light);
    }

    .btn.connect:active {
      background: var(--primary-green);
    }

    .btn.connected {
      margin-left: -10px;
      background: var(--light-bg);
      color: var(--text-dark);
    }

    .btn.logout {
      color: #fa4e4eff;
      font-weight: 600;
      border: 1px solid #721c24;
      border-radius: 16px;
      padding: 4px 12px;
      transition: all 0.3s ease;
    }
    .btn.logout:hover {
      color: var(--text-light);
      background: #fa4e4eff;
    }

/* People and Jobs Cards */
.people-card {
  grid-column: span 7;
  background: var(--card);
  backdrop-filter: blur(6px);
  border-radius: 12px;
  padding: 16px;
  box-shadow: var(--shadow);
  border: 1px solid var(--glass-border);
}

.jobs-card {
  grid-column: span 5;
  background: var(--card);
  backdrop-filter: blur(6px);
  border-radius: 12px;
  padding: 16px;
  box-shadow: var(--shadow);
  border: 1px solid var(--glass-border);
}

/* Section Titles */
.section-title {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 12px;
}

.controls {
  display: flex;
  gap: 8px;
}

.pill {
  padding: 6px 10px;
  border-radius: 999px;
  border: 1px solid rgba(25, 78, 46, 0.04);
  background: transparent;
  font-size: 13px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.pill:hover {
  background: #94f18f;
}

.cards-grid {
    margin: 10px 0;
    padding: 10px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    z-index: 0;
}

/* People Grid */
.people-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 12px;
}

.person {
  background: white;
  border-radius: 10px;
  padding: 10px;
  border: 1px solid #eefbf0;
  display: flex;
  gap: 10px;
  align-items: center;
  transition: transform 0.12s ease, box-shadow 0.12s ease;
}

.person:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 40px rgba(9, 30, 68, 0.06);
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  background: linear-gradient(135deg, var(--bg1), var(--bg2));
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: var(--text-dark);
}

.person .meta {
  flex: 1;
}

.person .name {
  font-weight: 700;
}

.person .muted {
  color: var(--muted);
  font-size: 13px;
}

/* Jobs List */
.jobs-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 10px;
  z-index: 0;
}

.job {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px;
  border-radius: 10px;
  border: 1px solid #eef2f7;
  background: white;
  transition: transform 0.12s ease;
}

.job:hover {
  transform: translateY(-4px);
}

.job .company {
  font-weight: 700;
}

.job .tags {
  font-size: 12px;
  color: var(--muted);
}

/* Right Column */
.right-col {
    margin: 10px 0;
  grid-column: span 12;
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.muted {
  font-size: 14px;
}



/* Helpers */
.muted {
  color: var(--muted);
}

footer {
    background: #f1f6f2;
    border-top: 1px solid #d4e7d6;
}

.footer {
    text-align: center;
    padding: 1.5rem 0 1rem 0;
    margin-top: 0;
}

.social-icons-footer {
    margin-top: 0.5rem;
}

.social-icons-footer .icon {
    color: var(--primary-green);
    margin: 0 0.5rem;
    font-size: 1.2rem;
    text-decoration: none;
    transition: color 0.2s;
}

.social-icons-footer .icon:hover {
    color: var(--text-color);
}


/* Responsive */
@media (max-width: 1000px) {
  .nav {
    display: none;
    flex-direction: column;
    position: absolute;
    top: 70px;
    right: 20px;
    background: white;
    padding: 1rem;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    z-index: 999;
  }

  .nav.active {
    display: flex;
  }

  .nav li a:hover {
    background: #93cf90;
    color: var(--text-light);
    border-radius: 8px;
    padding: 5px 10px;
  }
  .nav.active {
    display: flex;
  }

  .ham-menu {
    display: block;
  }
  .grid {
    grid-template-columns: repeat(6, 1fr);
  }

  .profile-card {
    grid-column: span 6;
  }

  .stats-wrap {
    grid-column: span 6;
    flex-direction: column;
  }

  .people-card {
    grid-column: span 6;
  }

  .jobs-card {
    grid-column: span 6;
  }
}

@media (max-width: 600px) {
  .people-grid {
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  }
}
  </style>
</head>

<body>
  <!-- Header -->
  <header>
    <div class="container">
      <nav>
        <div class="logo">
          <a href="#"><span style="color: #77e64c;">Seek</span>Jobs</a>
        </div>
        <ul class="nav">
          <li><a href="jobs.html"><i class="fa-solid fa-briefcase"></i> Jobs</a></li>
          <li><a href="networks.html"><i class="fa-solid fa-users"></i> Network</a></li>
          <li><a href="resources.html"><i class="fa-solid fa-book"></i> Resources</a></li>
          <li><a href="dashboard.html"><i class="fa-solid fa-cloud"></i> Dashboard</a></li>
          <li><a href="logout.php" class="btn logout">Sign Out</a></li>
        </ul>
        <div class="ham-menu"><i class="fa fa-bars"></i></div>
      </nav>
    </div>
  </header>

  <!-- Main Dashboard Section -->
  <section id="hero" class="hero">
      <!-- Profile + Stats -->
      <div class="dashboard-grid">
        <div class="profile-card" id="profileCard">
            <div class="profile-pic">
                <h1>U</h1>
              <!--<img id="welcomeProfilePic" src="/css/img/SE.jpeg" alt="Profile Picture">-->
            </div>
          <div class="profile-name" id="profileName">User Name</div>
          <div class="profile-bio" id="profileBio">Short bio goes here. Click view for profile on dashboard</div>
          <div class="profile-actions">
            <button class="btn ghost" id="viewProfileBtn" onclick="window.open('dashboard.html', '_self')">View</button>
          </div>
        </div>

        <div class="stats-wrap">
          <div class="stat">
            <div class="label">Total Connections Made</div>
            <div id="connectionsMade" class="value">0</div>

            <div class="label">Total Jobs Applied</div>
            <div id="jobsApplied" class="value">0</div>

            <div class="label">Pending Applications</div>
            <div id="jobsPending" class="value">0</div>
          </div>
        </div>
      </div>

      <!-- Welcome Message -->
      <div class="welcome">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["fullname"]); ?> !</h1>
        <p>You are now logged in to <strong style="color:#77e64c">Seek <span style="color: #235347;">Jobs Ghana</span></strong>.</p>
        <p>Launch your career with opportunities across the country. Discover, connect, and grow effortlessly</p>
        <p class="motto">Empowering Ghana's Workforce, One Job at a Time.</p>
      </div>
  </section>

  <section class="cards-grid">
          <!-- Cards Grid: Jobs, People, Right Column -->

        <!-- Job Postings -->
        <div class="jobs-card">
          <div class="section-title">
            <h2>Job Postings</h2>
            <div class="controls">
              <button class="pill" id="filterAll">All</button>
              <button class="pill" id="filterOpen">Open</button>
              <button class="pill" id="filterApplied">Applied</button>
            </div>
          </div>
          <div id="jobsList" class="jobs-list"></div>
        </div>

        <!-- People You May Know -->
        <div class="people-card">
          <div class="section-title">
            <h2>People You May Know</h2>
            <div class="controls">
              <button class="pill" id="shufflePeople">Shuffle</button>
              <button class="pill" id="refreshPeople">Refresh</button>
            </div>
          </div>
          <div class="people-grid" id="peopleList"></div>
        </div>
      </div>

        <!-- Right Column / Quick Actions -->
        <div class="right-col">
          <div class="card quick-actions">
            <div>
              <h3 style="margin:0">Quick Actions</h3>
              <div class="muted">Shortcuts</div>
            </div>
            <div style="display:flex;gap:8px;margin:5px 0">
              <button id="addConnection" class="btn primary">Add Connection</button>
              <button id="applyRandomJob" class="btn apply">Apply Random</button>
            </div>
          </div>
        </div>

    </div>
  </section>

  <footer>
      <div class="footer">
        <p style="font-weight: 700; color: #235347;">Empowering Ghana's Workforce, One Job at a Time.</p>
        <p>&copy; 2025 Seek Jobs Ghana. All rights reserved.</p>
        <div class="social-icons-footer">
            <a href="#" target="_blank" class="icon"><i class="fa-brands fa-facebook"></i></a>
            <a href="#" target="_blank" class="icon"><i class="fa-brands fa-x"></i></a>
            <a href="https://www.instagram.com/_.ricchie/" target="_blank" class="icon"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://www.linkedin.com/in/richard-osei-amofa-113414286/?trk=public-profile-join-page" target="_blank" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
    </div>
  </footer>
  <script>
        // --- mock data ---
    const peopleMock = [
      {id:1,name:'Daniel Biggs',title:'Frontend Engineer',mutuals:8},
      {id:2,name:'Andrea Opare',title:'Product Manager',mutuals:3},
      {id:3,name:'Prince Ankrah',title:'Data Scientist',mutuals:12},
      {id:4,name:'Belinda Amofa',title:'UX Designer',mutuals:5},
      {id:5,name:'Peter Ofori',title:'Marketing Lead',mutuals:2},
      {id:6,name:'Charles Nyarko',title:'DevOps Engineer',mutuals:9}
    ];

    const jobsMock = [
      {id:101,role:'Frontend Engineer',company:'BlueWave',location:'Accra',status:'open'},
      {id:102,role:'Backend Engineer',company:'DataNest',location:'Kumasi',status:'applied'},
      {id:103,role:'Product Designer',company:'FlowStudio',location:'Accra',status:'open'},
      {id:104,role:'Data Analyst',company:'InsightLab',location:'Takoradi',status:'pending'}
    ];

    // --- app state ---
    const state = {
      people: [...peopleMock],
      jobs: [...jobsMock],
      connectionsMade: 24,
      jobsApplied: jobsMock.filter(j=>j.status==='applied').length + 1,
      jobsPending: jobsMock.filter(j=>j.status==='pending').length,
      activity: []
    };

    // --- dom refs ---
    const peopleList = document.getElementById('peopleList');
    const jobsList = document.getElementById('jobsList');
    const connectionsMadeEl = document.getElementById('connectionsMade');
    const jobsAppliedEl = document.getElementById('jobsApplied');
    const jobsPendingEl = document.getElementById('jobsPending');
    const activityLog = document.getElementById('activityLog');

    const profileName = document.getElementById('profileName');
    const profileBio = document.getElementById('profileBio');
    const profilePic = document.getElementById('profilePic');
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editModal = document.getElementById('editModal');
    const editName = document.getElementById('editName');
    const editBio = document.getElementById('editBio');
    const editPic = document.getElementById('editPic');
    const saveEdit = document.getElementById('saveEdit');
    const cancelEdit = document.getElementById('cancelEdit');
    const toast = document.getElementById('toast');
    const removePicBtn = document.getElementById('removePic');

    // --- helper functions ---
    function showToast(msg='Saved',time=2200){
      toast.textContent = msg;
      toast.classList.add('show');
      setTimeout(()=>toast.classList.remove('show'),time);
    }

    function addActivity(text){
      const time = new Date().toLocaleTimeString();
      state.activity.unshift({text,time});
      if(state.activity.length>8) state.activity.pop();
      renderActivity();
    }

    function renderActivity(){
      activityLog.innerHTML = '';
      state.activity.forEach(a=>{
        const d = document.createElement('div');
        d.textContent = `${a.time} — ${a.text}`;
        activityLog.appendChild(d);
      })
    }

    // --- profile persistence ---
    function loadProfile(){
      const stored = JSON.parse(localStorage.getItem('profileData')||'{}');
      if(stored.name) profileName.textContent = stored.name;
      if(stored.bio) profileBio.textContent = stored.bio;
      if(stored.pic) profilePic.innerHTML = `<img src="${stored.pic}" alt="profile"/>`;
      else profilePic.textContent = (stored.name||profileName.textContent||'U').split(' ').map(n=>n[0]).slice(0,2).join('');
    }

    // --- stats rendering ---
    function renderStats(){
      connectionsMadeEl.textContent = state.connectionsMade.toLocaleString();
      jobsAppliedEl.textContent = state.jobsApplied.toLocaleString();
      jobsPendingEl.textContent = state.jobsPending.toLocaleString();
    }

    // --- people & jobs rendering ---
    function renderPeople(){
      peopleList.innerHTML = '';
      state.people.forEach(p=>{
        const wrapper = document.createElement('div');
        wrapper.className='person';
        wrapper.innerHTML = `
          <div class='avatar'>${p.name.split(' ').map(n=>n[0]).slice(0,2).join('')}</div>
          <div class='meta'>
            <div class='name'>${p.name}</div>
            <div class='muted'>${p.title} • ${p.mutuals} mutuals</div>
          </div>
          <div><button class='btn connect' data-id='${p.id}' data-action='connect'>Connect</button></div>
        `;
        peopleList.appendChild(wrapper);
      })
      document.querySelectorAll('.btn.connect').forEach(button => {
        button.addEventListener('click', () => {
        button.textContent = "Connected";
        button.classList.add('connected');
    });
    });
    }

    let currentFilter = 'all';
    function renderJobs(){
      jobsList.innerHTML = '';
      state.jobs.filter(j=>{
        if(currentFilter==='open') return j.status==='open';
        if(currentFilter==='applied') return j.status==='applied';
        return true;
      }).forEach(j=>{
        const el = document.createElement('div');
        el.className='job';
        el.innerHTML = `
          <div>
            <div class='company'>${j.role} — ${j.company}</div>
            <div class='tags muted'>${j.location} • ${j.status}</div>
          </div>
          <div style='display:flex;gap:8px'>
            <button class='btn apply' data-id='${j.id}' data-action='apply'>${j.status==='open'?'Apply':(j.status==='applied'?'Applied':'Pending')}</button>
            <button class='btn save' data-id='${j.id}' data-action='save'>Save</button>
          </div>
        `;
        jobsList.appendChild(el);
      })
    }

    // --- save state ---
    function saveState(){
      const minimal = { jobs: state.jobs, people: state.people, connectionsMade: state.connectionsMade, jobsApplied: state.jobsApplied, jobsPending: state.jobsPending };
      localStorage.setItem('dashboardState', JSON.stringify(minimal));
    }
    function loadState(){
      const stored = JSON.parse(localStorage.getItem('dashboardState')||'null');
      if(stored){
        state.jobs = stored.jobs;
        state.people = stored.people;
        state.connectionsMade = stored.connectionsMade||state.connectionsMade;
        state.jobsApplied = stored.jobsApplied||state.jobsApplied;
        state.jobsPending = stored.jobsPending||state.jobsPending
      }
    }

    // --- event handlers ---
    document.addEventListener('click',(e)=>{
      const btn = e.target.closest('button');
      if(!btn) return;
      const action = btn.dataset.action;
      const id = btn.dataset.id && Number(btn.dataset.id);
      if(action==='connect' && id){
        const p = state.people.find(x=>x.id===id);
        if(p){ state.connectionsMade++; addActivity(`Connected with ${p.name}`); saveState(); renderStats(); }
      }
      if(action==='apply' && id){
        const j = state.jobs.find(x=>x.id===id);
        if(j && j.status==='open'){ j.status='applied'; state.jobsApplied++; addActivity(`Applied to ${j.role} at ${j.company}`); saveState(); renderStats(); renderJobs(); }
      }
    });

    document.getElementById('addConnection').addEventListener('click',()=>{
      const rand = state.people[Math.floor(Math.random()*state.people.length)];
      state.connectionsMade++; addActivity(`Connected with ${rand.name}`); saveState(); renderStats();
    });

    document.getElementById('applyRandomJob').addEventListener('click',()=>{
      const openJobs = state.jobs.filter(j=>j.status==='open');
      if(openJobs.length===0) return alert('No open jobs');
      const j = openJobs[Math.floor(Math.random()*openJobs.length)];
      j.status='applied'; state.jobsApplied++; addActivity(`Applied to ${j.role} at ${j.company}`); saveState(); renderStats(); renderJobs();
    });

    document.getElementById('shufflePeople').addEventListener('click',()=>{
      state.people.sort(()=>Math.random()-0.5);
      renderPeople();
      addActivity('Shuffled people suggestions');
    });

    document.getElementById('refreshPeople').addEventListener('click',()=> {
      renderPeople();
      addActivity('Refreshed people suggestions');
    });

    document.getElementById('filterAll').addEventListener('click',()=>{ currentFilter='all'; renderJobs(); });
    document.getElementById('filterOpen').addEventListener('click',()=>{ currentFilter='open'; renderJobs(); });
    document.getElementById('filterApplied').addEventListener('click',()=>{ currentFilter='applied'; renderJobs(); });

    // --- modal handlers ---
    editProfileBtn.addEventListener('click',()=>{
      editModal.style.display='flex';
      editName.value = profileName.textContent;
      editBio.value = profileBio.textContent==='Short bio goes here. Click edit to update.'? '': profileBio.textContent;
      document.body.style.overflow='hidden';
      document.getElementById('uploadHint').textContent = '';
      editPic.value = '';

      const storedProfile = JSON.parse(localStorage.getItem('profileData') || '{}');
      removePicBtn.style.display = storedProfile.pic ? 'inline-block' : 'none';
    });

    cancelEdit.addEventListener('click',()=>{
      editModal.style.display='none';
      document.body.style.overflow='auto';
    });

    // --- save edit profile ---
    saveEdit.addEventListener('click',()=>{
      const name = editName.value.trim() || 'User Name';
      const bio = editBio.value.trim() || 'No bio provided.';
      const file = editPic.files[0];
      const stored = JSON.parse(localStorage.getItem('profileData') || '{}');
      const data = { name, bio };

      if (stored.pic && !file) data.pic = stored.pic;

      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          document.getElementById('uploadHint').textContent = 'Image too large. Max 2MB.';
          return;
        }
        profilePic.style.filter = 'blur(4px)';
        const reader = new FileReader();
        reader.onload = e => {
          setTimeout(() => {
            profilePic.innerHTML = `<img src='${e.target.result}' alt='profile'/>`;
            profilePic.style.filter = '';
            data.pic = e.target.result;
            localStorage.setItem('profileData', JSON.stringify(data));
            profileName.textContent = name;
            profileBio.textContent = bio;
            addActivity('Updated profile');
            showToast('Profile updated');
            editModal.style.display='none';
            document.body.style.overflow='auto';
            removePicBtn.style.display='inline-block';
          }, 550);
        };
        reader.readAsDataURL(file);
      } else {
        profileName.textContent = name;
        profileBio.textContent = bio;
        const initials = name.split(' ').map(n => n[0]).slice(0,2).join('');
        if (!data.pic) profilePic.textContent = initials;
        localStorage.setItem('profileData', JSON.stringify(data));
        addActivity('Profile updated');
        showToast('Profile updated');
        editModal.style.display='none';
        document.body.style.overflow='auto';
      }
    });

    // --- remove profile button ---
    removePicBtn.addEventListener('click',()=>{
      profilePic.innerHTML = '';
      const initials = profileName.textContent.split(' ').map(n => n[0]).slice(0,2).join('');
      profilePic.textContent = initials;
      const stored = JSON.parse(localStorage.getItem('profileData') || '{}');
      stored.pic = null;
      localStorage.setItem('profileData', JSON.stringify(stored));
      removePicBtn.style.display = 'none';
      addActivity('Removed profile picture');
      showToast('Profile picture removed');
    });

    // --- init ---
    loadState();
    loadProfile();
    renderStats();
    renderPeople();
    renderJobs();
    renderActivity();
  </script>
</body>
</html>
