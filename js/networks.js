// Handle Connect button
document.querySelectorAll('.connect-btn').forEach(button => {
  button.addEventListener('click', () => {
    button.textContent = "Connected";
    button.classList.add('connected');
  });
});

// Handle Accept/Ignore buttons
document.querySelectorAll('.accept-btn').forEach(button => {
  button.addEventListener('click', () => {
    const inviteItem = button.closest('li');
    inviteItem.remove();
  });
});

document.querySelectorAll('.ignore-btn').forEach(button => {
  button.addEventListener('click', () => {
    const inviteItem = button.closest('li');
    inviteItem.remove();
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const nameEl = document.getElementById('networkName');
  const titleEl = document.getElementById('networkTitle');
  const bioEl = document.getElementById('networkBio');
  const picEl = document.getElementById('networkProfilePic');

  function updateProfileView(data) {
    if (!data) return;
    nameEl.textContent = data.name || 'User Name';
    titleEl.textContent = data.title || 'Job Seeker';
    bioEl.textContent = data.bio || 'No bio provided.';

    if (data.pic) {
      picEl.src = data.pic;
      picEl.style.display = 'block';
    } else {
      picEl.style.display = 'flex';
    }
  }

  // Initial load
  const stored = JSON.parse(localStorage.getItem('profileData') || '{}');
  updateProfileView(stored);

  // Live update when profile changes on another tab/page
  window.addEventListener('storage', (event) => {
    if (event.key === 'profileData') {
      const newProfile = JSON.parse(event.newValue || '{}');
      updateProfileView(newProfile);
    }
  });
});

    // --- stats rendering ---
    function renderStats(){
      connectionsMadeEl.textContent = state.connectionsMade.toLocaleString();
      jobsAppliedEl.textContent = state.jobsApplied.toLocaleString();
      jobsPendingEl.textContent = state.jobsPending.toLocaleString();
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

document.addEventListener('DOMContentLoaded', () => {
  const jobsPendingEl = document.getElementById('jobsPending');
  jobsPendingEl.style.color = "red";

  const jobsAppliedEl = document.getElementById('jobsApplied');
  jobsAppliedEl.style.color = "var(--text-color)";

  const connectionsMadeEl = document.getElementById('connectionsMade');
  connectionsMadeEl.style.color = "limegreen";
});
