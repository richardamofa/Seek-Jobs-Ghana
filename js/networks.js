// Handle Connect button
document.querySelectorAll('.connect-btn').forEach(button => {
  button.addEventListener('click', () => {
    button.textContent = "Connected";
    button.classList.add('connected');
  });
});

/*Handle Accept/Ignore buttons
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
});*/

document.addEventListener('DOMContentLoaded', () => {
  const nameEl = document.getElementById('networkName');
  const titleEl = document.getElementById('networkTitle');
  const bioEl = document.getElementById('networkBio');
  const picEl = document.getElementById('networkProfilePic');

  function updateProfileView(data) {
    if (!data) return;
    nameEl.textContent = data.name || 'User Name';
    titleEl.textContent = data.title || 'Job Seeker';
    bioEl.textContent = data.bio || 'Short bio goes here.';

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

  // Live update when profile changes on dashboard 
  window.addEventListener('storage', (event) => {
    if (event.key === 'profileData') {
      const newProfile = JSON.parse(event.newValue || '{}');
      updateProfileView(newProfile);
    }
  });
});

function saveState() {
  }

function loadState() {
  }

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

    function saveState() {

    }
    function loadState() {

    }

document.addEventListener('DOMContentLoaded', () => {
  const jobsPendingEl = document.getElementById('jobsPending');
  jobsPendingEl.style.color = "red";

  const jobsAppliedEl = document.getElementById('jobsApplied');
  jobsAppliedEl.style.color = "var(--text-color)";

  const connectionsMadeEl = document.getElementById('connectionsMade');
  connectionsMadeEl.style.color = "limegreen";
});

// -- Contact Section Information
document.addEventListener('DOMContentLoaded', () => {
  const editContactBtn = document.getElementById('edit-contact-btn');
  const saveContactBtn = document.getElementById('save-contact-btn');
  const editSkillsBtn = document.getElementById('edit-skills-btn');
  const saveSkillsBtn = document.getElementById('save-skills-btn');

  // --- Contact Section ---
  editContactBtn.addEventListener('click', () => {
    ['email', 'number', 'links'].forEach(id => {
      document.getElementById(`contact-${id}`).style.display = 'none';
      document.getElementById(`edit-${id}`).style.display = 'inline-block';
    });
    editContactBtn.style.display = 'none';
    saveContactBtn.style.display = 'inline-block';
  });

  saveContactBtn.addEventListener('click', () => {
    ['email', 'number', 'links'].forEach(id => {
      const newValue = document.getElementById(`edit-${id}`).value.trim();
      document.getElementById(`contact-${id}`).textContent = newValue;
      document.getElementById(`contact-${id}`).style.display = 'inline';
      document.getElementById(`edit-${id}`).style.display = 'none';
    });
    saveContactBtn.style.display = 'none';
    editContactBtn.style.display = 'inline-block';
    localStorage.setItem('contactInfo', JSON.stringify({
      email: editEmail.value,
      number: editNumber.value,
      links: editLinks.value
    }));
  });

  // --- Roles & Skills Section ---
  editSkillsBtn.addEventListener('click', () => {
    document.getElementById('skills-list').style.display = 'none';
    document.getElementById('edit-skills').style.display = 'block';
    editSkillsBtn.style.display = 'none';
    saveSkillsBtn.style.display = 'inline-block';
  });

  saveSkillsBtn.addEventListener('click', () => {
    const newSkills = document.getElementById('edit-skills').value.trim().split('\n');
    const skillsList = document.getElementById('skills-list');
    skillsList.innerHTML = newSkills.map(skill => `<li>${skill}</li>`).join('');
    skillsList.style.display = 'block';
    document.getElementById('edit-skills').style.display = 'none';
    saveSkillsBtn.style.display = 'none';
    editSkillsBtn.style.display = 'inline-block';
    localStorage.setItem('skills', JSON.stringify(newSkills));
  });

  // --- Load saved data if exists ---
  const savedContact = JSON.parse(localStorage.getItem('contactInfo'));
  if (savedContact) {
    contactEmail.textContent = savedContact.email;
    contactNumber.textContent = savedContact.number;
    contactLinks.textContent = savedContact.links;
  }

  const savedSkills = JSON.parse(localStorage.getItem('skills'));
  if (savedSkills) {
    skillsList.innerHTML = savedSkills.map(skill => `<li>${skill}</li>`).join('');
  }
});
