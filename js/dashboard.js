    // --- mock data ---
    const peopleMock = [
      {id:1,name:'Aisha Mensah',title:'Frontend Engineer',mutuals:8},
      {id:2,name:'Kwame Boateng',title:'Product Manager',mutuals:3},
      {id:3,name:'Efua Asante',title:'Data Scientist',mutuals:12},
      {id:4,name:'Daniel Owusu',title:'UX Designer',mutuals:5},
      {id:5,name:'Rita Appiah',title:'Marketing Lead',mutuals:2},
      {id:6,name:'Joseph Darko',title:'DevOps Engineer',mutuals:9}
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

    // --- People & Jobs Rendering ---
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
          <div><button class='btn primary' data-id='${p.id}' data-action='connect'>Connect</button></div>
        `;
        peopleList.appendChild(wrapper);
      })
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
            <button class='btn' data-id='${j.id}' data-action='apply'>${j.status==='open'?'Apply':(j.status==='applied'?'Applied':'Pending')}</button>
            <button class='btn' data-id='${j.id}' data-action='save'>Save</button>
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

    document.getElementById('refreshPeople').addEventListener('click',()=>{
      renderPeople();
      addActivity('Refreshed people suggestions');
    });

    document.getElementById('filterAll').addEventListener('click',()=>{ currentFilter='all'; renderJobs(); });
    document.getElementById('filterOpen').addEventListener('click',()=>{ currentFilter='open'; renderJobs(); });
    document.getElementById('filterApplied').addEventListener('click',()=>{ currentFilter='applied'; renderJobs(); });

    // --- MODAL HANDLERS ---
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