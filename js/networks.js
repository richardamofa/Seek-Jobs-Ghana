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
