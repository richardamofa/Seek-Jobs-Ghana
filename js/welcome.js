(() => {
  // --- mock data ---
  const peopleMock = [
    { id: 1, name: 'Daniel Biggs', title: 'Frontend Engineer', mutuals: 8 },
    { id: 2, name: 'Andrea Opare', title: 'Cloud Manager', mutuals: 3 },
    { id: 3, name: 'Prince Ankrah', title: 'Data Scientist', mutuals: 12 },
    { id: 4, name: 'Belinda Amofa', title: 'UI/UX Designer', mutuals: 5 },
    { id: 5, name: 'Peter Ofori', title: 'Marketing Lead', mutuals: 2 },
    { id: 6, name: 'Charles Nyarko', title: 'Software Engineer', mutuals: 9 }
  ];

  const jobsMock = [
    { id: 101, role: 'Frontend Engineer', company: 'HighTech', location: 'Accra', status: 'open' },
    { id: 102, role: 'Backend Engineer', company: 'DataNest', location: 'Kumasi', status: 'open' },
    { id: 103, role: 'Product Designer', company: 'FIGO', location: 'Accra', status: 'open' },
    { id: 104, role: 'Data Analyst', company: 'InsightLab', location: 'Takoradi', status: 'open' },
    { id: 105, role: 'AI Engineer', company: 'StarTech', location: 'Accra', status: 'open' },
    { id: 106, role: 'Backend Engineer', company: 'BlueWave', location: 'Kumasi', status: 'open' },
    { id: 107, role: 'AI Developer', company: 'Vivara Inc', location: 'Accra', status: 'open' },
    { id: 108, role: 'Vibe Coder', company: 'YourHouse', location: 'Takoradi', status: 'open' }
  ];

  // --- element refs ---
  const peopleList = document.getElementById('peopleList');
  const jobsList = document.getElementById('jobsList');
  const connectionsMadeEl = document.getElementById('connectionsMade');
  const jobsAppliedEl = document.getElementById('jobsApplied');
  const jobsPendingEl = document.getElementById('jobsPending');

  const shuffleBtn = document.getElementById('shufflePeople');
  const refreshBtn = document.getElementById('refreshPeople');
  const filterAllBtn = document.getElementById('filterAll');
  const filterOpenBtn = document.getElementById('filterOpen');
  const filterAppliedBtn = document.getElementById('filterApplied');

  const addConnectionBtn = document.getElementById('addConnection');
  const applyRandomBtn = document.getElementById('applyRandomJob');

  // --- profile elements ---
  const profileName = document.getElementById('profileNameDisplay');
  const profileTitle = document.getElementById('profileTitleDisplay'); // optional
  const profileBio = document.getElementById('profileBioDisplay');
  const profilePic = document.getElementById('profilePicDisplay');

  const PROFILE_KEY = 'profileData';
  const STORAGE_KEY = 'seek_welcome_state';

  // --- toast setup ---
  let toast = document.getElementById('toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toast';
    Object.assign(toast.style, {
      position: 'fixed',
      right: '20px',
      bottom: '20px',
      padding: '10px 14px',
      borderRadius: '14px',
      background: '#66f12f',
      color: '#fff',
      fontWeight: '600',
      zIndex: 9999,
      opacity: 0,
      transition: 'opacity .2s ease, transform .2s ease'
    });
    document.body.appendChild(toast);
  }

  function showToast(message = 'Saved', ms = 1800) {
    toast.textContent = message;
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';
    clearTimeout(showToast._t);
    showToast._t = setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(8px)';
    }, ms);
  }

  // Text color for renders
  document.addEventListener('DOMContentLoaded', () => {
  const jobsPendingEl = document.getElementById('jobsPending');
  jobsPendingEl.style.color = "red";

  const jobsAppliedEl = document.getElementById('jobsApplied');
  jobsAppliedEl.style.color = "var(--text-color)";

  const connectionsMadeEl = document.getElementById('connectionsMade');
  connectionsMadeEl.style.color = "limegreen";
});

  // --- app state ---
  let state = {
    people: [...peopleMock],
    jobs: [...jobsMock],
    connectionsMade: 0,
    activity: [],
    currentFilter: 'all'
  };

  function saveState() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(state));
    } catch (e) { console.warn('Could not save state', e); }
  }

  function loadState() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return;
      const stored = JSON.parse(raw);
      state.people = stored.people || [...peopleMock];
      state.jobs = stored.jobs || [...jobsMock];
      state.connectionsMade = stored.connectionsMade || 0;
      state.activity = stored.activity || [];
    } catch (e) { console.warn('Could not load state', e); }
  }

  // --- profile sync ---
  function loadProfile() {
    const stored = JSON.parse(localStorage.getItem(PROFILE_KEY) || '{}');
    if (!stored) return;

    if (profileName) profileName.textContent = stored.name || 'User Name';
    if (profileTitle) profileTitle.textContent = stored.title || 'Job Seeker';
    if (profileBio) profileBio.textContent = stored.bio || 'Short bio goes here.';

    if (profilePic) {
      if (stored.pic) {
        profilePic.innerHTML = `<img src="${stored.pic}" alt="profile"/>`;
      } else {
        const initials = (stored.name || 'U').split(' ').map(n => n[0]).slice(0, 2).join('');
        profilePic.textContent = initials;
      }
    }
  }

  window.addEventListener('storage', (event) => {
    if (event.key === PROFILE_KEY) loadProfile();
  });

  // --- helper functions ---
  function addActivity(text) {
    const time = new Date().toLocaleTimeString();
    state.activity.unshift({ text, time });
    if (state.activity.length > 12) state.activity.length = 12;
    saveState();
  }

  function escapeHtml(str) {
    if (typeof str !== 'string') return str;
    return str.replace(/[&<>"']/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[s]));
  }

  // --- rendering ---
  function renderStats() {
    if (connectionsMadeEl) connectionsMadeEl.textContent = state.connectionsMade.toLocaleString();
    if (jobsAppliedEl) jobsAppliedEl.textContent = state.jobs.filter(j => j.status === 'applied').length.toLocaleString();
    if (jobsPendingEl) jobsPendingEl.textContent = state.jobs.filter(j => j.status === 'pending').length.toLocaleString();
  }

  function renderPeople() {
    if (!peopleList) return;
    peopleList.innerHTML = '';
    state.people.forEach(p => {
      const wrapper = document.createElement('div');
      wrapper.className = 'person';
      wrapper.innerHTML = `
        <div class="avatar">${p.name.split(' ').map(n=>n[0]).slice(0,2).join('')}</div>
        <div class="meta">
          <div class="name">${p.name}</div>
          <div class="muted">${p.title} • ${p.mutuals} mutuals</div>
        </div>
        <div>
          <button class="btn connect" data-id="${p.id}" data-action="connect">Connect</button>
        </div>
      `;
      peopleList.appendChild(wrapper);
    });
  }

  function createJobElement(j) {
    const el = document.createElement('div');
    el.className = 'job';
    const statusLabel = (j.status === 'open') ? 'Apply' : (j.status === 'applied' ? 'Applied' : 'Pending');
    const disabled = j.status !== 'open' ? 'disabled' : '';
    el.innerHTML = `
      <div>
        <div class="company">${escapeHtml(j.role)} — ${escapeHtml(j.company)}</div>
        <div class="tags muted">${escapeHtml(j.location)} • ${escapeHtml(j.status)}</div>
      </div>
      <div style="display:flex;gap:8px">
        <button class="btn apply" data-id="${j.id}" data-action="apply" ${disabled}>${statusLabel}</button>
        <button class="btn save" data-id="${j.id}" data-action="save">Save</button>
      </div>
    `;
    return el;
  }

  function renderJobs() {
    if (!jobsList) return;
    jobsList.innerHTML = '';
    const filtered = state.jobs.filter(j => {
      if (state.currentFilter === 'open') return j.status === 'open';
      if (state.currentFilter === 'applied') return j.status === 'applied';
      return true;
    });
    if (filtered.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'muted';
      empty.textContent = 'No jobs to show.';
      jobsList.appendChild(empty);
      return;
    }
    filtered.forEach(j => jobsList.appendChild(createJobElement(j)));
  }

  // --- actions ---
  function connectToPerson(id) {
    const p = state.people.find(x => x.id === id);
    if (!p) return;
    state.connectionsMade++;
    addActivity(`Connected with ${p.name}`);
    renderStats();
    saveState();
    showToast(`Connected with ${p.name}`);
  }

  function applyToJob(id) {
    const j = state.jobs.find(x => x.id === id);
    if (!j || j.status !== 'open') return;
    j.status = 'applied';
    addActivity(`Applied to ${j.role} at ${j.company}`);
    saveState();
    renderStats();
    renderJobs();
    showToast(`Applied to ${j.role}`);
  }

  function shufflePeople() {
    for (let i = state.people.length - 1; i > 0; i--) {
      const r = Math.floor(Math.random() * (i + 1));
      [state.people[i], state.people[r]] = [state.people[r], state.people[i]];
    }
    addActivity('Shuffled people suggestions');
    saveState();
    renderPeople();
    showToast('Shuffled people');
  }

  function refreshPeople() {
    state.people = [...peopleMock];
    addActivity('Refreshed people suggestions');
    saveState();
    renderPeople();
    showToast('Refreshed people');
  }

  // --- event wiring ---
  function wireEvents() {
    if (shuffleBtn) shuffleBtn.addEventListener('click', shufflePeople);
    if (refreshBtn) refreshBtn.addEventListener('click', refreshPeople);
    if (filterAllBtn) filterAllBtn.addEventListener('click', () => { state.currentFilter='all'; renderJobs(); });
    if (filterOpenBtn) filterOpenBtn.addEventListener('click', () => { state.currentFilter='open'; renderJobs(); });
    if (filterAppliedBtn) filterAppliedBtn.addEventListener('click', () => { state.currentFilter='applied'; renderJobs(); });
    if (addConnectionBtn) addConnectionBtn.addEventListener('click', () => {
      const rand = state.people[Math.floor(Math.random()*state.people.length)];
      if (rand) connectToPerson(rand.id);
    });
    if (applyRandomBtn) applyRandomBtn.addEventListener('click', () => {
      const openJobs = state.jobs.filter(j => j.status === 'open');
      if (openJobs.length) applyToJob(openJobs[Math.floor(Math.random()*openJobs.length)].id);
      else showToast('No open jobs', 1200);
    });

    document.addEventListener('click', e => {
      const btn = e.target.closest('button');
      if (!btn) return;
      const id = btn.dataset.id ? Number(btn.dataset.id) : null;
      if (btn.dataset.action === 'connect' && id) {
        btn.textContent='Connected'; btn.classList.add('connected');
        connectToPerson(id);
      }
      if (btn.dataset.action === 'apply' && id) {
        btn.disabled=true; applyToJob(id);
      }
      if (btn.dataset.action === 'save' && id) {
        addActivity(`Saved job ${id}`); showToast('Saved job');
      }
    });
  }

  // --- init ---
  function init() {
    loadState();
    loadProfile();
    renderPeople();
    renderJobs();
    renderStats();
    wireEvents();
  }

  if (document.readyState==='loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else init();

})();
