(() => {
  // --- mock data (replace with server-provided data later if needed) ---
  const peopleMock = [
    { id: 1, name: 'Daniel Biggs', title: 'Frontend Engineer', mutuals: 8 },
    { id: 2, name: 'Andrea Opare', title: 'Product Manager', mutuals: 3 },
    { id: 3, name: 'Prince Ankrah', title: 'Data Scientist', mutuals: 12 },
    { id: 4, name: 'Belinda Amofa', title: 'UX Designer', mutuals: 5 },
    { id: 5, name: 'Peter Ofori', title: 'Marketing Lead', mutuals: 2 },
    { id: 6, name: 'Charles Nyarko', title: 'DevOps Engineer', mutuals: 9 }
  ];

  const jobsMock = [
    { id: 101, role: 'Frontend Engineer', company: 'BlueWave', location: 'Accra', status: 'open' },
    { id: 102, role: 'Backend Engineer', company: 'DataNest', location: 'Kumasi', status: 'open' },
    { id: 103, role: 'Product Designer', company: 'FlowStudio', location: 'Accra', status: 'open' },
    { id: 104, role: 'Data Analyst', company: 'InsightLab', location: 'Takoradi', status: 'open' },
        { id: 101, role: 'Frontend Engineer', company: 'BlueWave', location: 'Accra', status: 'open' },
    { id: 102, role: 'Backend Engineer', company: 'DataNest', location: 'Kumasi', status: 'open' },
    { id: 103, role: 'Product Designer', company: 'FlowStudio', location: 'Accra', status: 'open' },
    { id: 104, role: 'Data Analyst', company: 'InsightLab', location: 'Takoradi', status: 'open' }
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

  // Toast element (create if not present)
  let toast = document.getElementById('toast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'toast';
    toast.style.position = 'fixed';
    toast.style.right = '20px';
    toast.style.bottom = '20px';
    toast.style.padding = '10px 14px';
    toast.style.borderRadius = '14px';
    toast.style.background = '#66f12f';
    toast.style.color = '#fff';
    toast.style.fontWeight = '600';
    toast.style.zIndex = 9999;
    toast.style.opacity = 0;
    toast.style.transition = 'opacity .2s ease, transform .2s ease';
    document.body.appendChild(toast);
  }

  document.addEventListener('DOMContentLoaded', () => {
    const jobsPendingEl = document.getElementById('jobsPending');
    jobsPendingEl.style.color = "red";

    const jobsAppliedEl = document.getElementById('jobsApplied');
    jobsAppliedEl.style.color = "var(--text-color)";

    const connectionsMadeEl = document.getElementById('connectionsMade');
    connectionsMadeEl.style.color = "limegreen";
  });

  // --- app state ---
  const STORAGE_KEY = 'seek_welcome_state';
  let state = {
    people: [...peopleMock],
    jobs: [...jobsMock],
    connectionsMade: 0,
    activity: [],
    // UI
    currentFilter: 'all' // all | open | applied
  };

  // --- helpers ---
  function saveState() {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify({
        people: state.people,
        jobs: state.jobs,
        connectionsMade: state.connectionsMade,
        activity: state.activity
      }));
    } catch (e) {
      // localStorage could be disabled; ignore gracefully
      console.warn('Could not save state', e);
    }
  }

  function loadState() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) return;
      const stored = JSON.parse(raw);
      if (stored.people) state.people = stored.people;
      if (stored.jobs) state.jobs = stored.jobs;
      if (typeof stored.connectionsMade === 'number') state.connectionsMade = stored.connectionsMade;
      if (Array.isArray(stored.activity)) state.activity = stored.activity;
    } catch (e) {
      console.warn('Could not load state', e);
    }
  }

   function saveState() {
    }

    function loadState() {
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

  function addActivity(text) {
    const time = new Date().toLocaleTimeString();
    state.activity.unshift({ text, time });
    if (state.activity.length > 12) state.activity.length = 12;
    saveState();
    // No visible activity area in welcome page by default, but keep it in state
  }

  // --- rendering ---
  function renderStats() {
    connectionsMadeEl.textContent = Number(state.connectionsMade || 0).toLocaleString();
    jobsAppliedEl.textContent = state.jobs.filter(j => j.status === 'applied').length.toLocaleString();
    jobsPendingEl.textContent = state.jobs.filter(j => j.status === 'pending').length.toLocaleString();
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

  // utility to create job element
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

    filtered.forEach(j => {
      jobsList.appendChild(createJobElement(j));
    });
  }

  // --- actions ---
  function connectToPerson(id) {
    const p = state.people.find(x => x.id === id);
    if (!p) return;
    state.connectionsMade = (state.connectionsMade || 0) + 1;
    addActivity(`Connected with ${p.name}`);
    saveState();
    renderStats();
    showToast(`Connected with ${p.name}`);
  }

  function applyToJob(id) {
    const j = state.jobs.find(x => x.id === id);
    if (!j) return;
    if (j.status !== 'open') return;
    j.status = 'applied';
    addActivity(`Applied to ${j.role} at ${j.company}`);
    saveState();
    renderStats();
    renderJobs();
    showToast(`Applied to ${j.role}`);
  }

  function applyRandomJob() {
    const openJobs = state.jobs.filter(j => j.status === 'open');
    if (openJobs.length === 0) {
      showToast('No open jobs available', 1500);
      return;
    }
    const pick = openJobs[Math.floor(Math.random() * openJobs.length)];
    applyToJob(pick.id);
  }

  function shufflePeople() {
    // Fisher-Yates shuffle
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
    // For now reset to initial mock (simulate refresh). If you connect this to an API, fetch new people here.
    state.people = [...peopleMock];
    saveState();
    renderPeople();
    addActivity('Refreshed people suggestions');
    showToast('Refreshed people');
  }

  // --- utility: escape html for safety when injecting text ---
  function escapeHtml(str) {
    if (typeof str !== 'string') return str;
    return str.replace(/[&<>"']/g, s => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    }[s]));
  }

  // --- event wiring ---
  function wireEvents() {
    // Buttons that exist
    if (shuffleBtn) shuffleBtn.addEventListener('click', shufflePeople);
    if (refreshBtn) refreshBtn.addEventListener('click', refreshPeople);

    if (filterAllBtn) filterAllBtn.addEventListener('click', () => { state.currentFilter = 'all'; renderJobs(); });
    if (filterOpenBtn) filterOpenBtn.addEventListener('click', () => { state.currentFilter = 'open'; renderJobs(); });
    if (filterAppliedBtn) filterAppliedBtn.addEventListener('click', () => { state.currentFilter = 'applied'; renderJobs(); });

    if (addConnectionBtn) addConnectionBtn.addEventListener('click', () => {
      // connect to a random suggested person
      const rand = state.people[Math.floor(Math.random() * state.people.length)];
      if (!rand) return showToast('No people found');
      connectToPerson(rand.id);
    });

    if (applyRandomBtn) applyRandomBtn.addEventListener('click', applyRandomJob);

    // Event delegation for job & person actions
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('button');
      if (!btn) return;
      const action = btn.dataset.action;
      const id = btn.dataset.id ? Number(btn.dataset.id) : null;

      if (action === 'connect' && id) {
        // immediate UI feedback
        btn.textContent = 'Connected';
        btn.classList.add('connected');
        connectToPerson(id);
      }

      if (action === 'apply' && id) {
        // disable the button immediately to avoid double-submits
        btn.disabled = true;
        applyToJob(id);
      }

      if (action === 'save' && id) {
        // placeholder save behavior
        showToast('Saved job to your list');
        addActivity(`Saved job ${id}`);
      }
    });
  }

  // --- init ---
  function init() {
    loadState();
    renderPeople();
    renderJobs();
    renderStats();
    wireEvents();
  }

  // run after DOM loaded
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
