function sendMessage() {
    let userInput = document.getElementById("userInput").value;
    if (!userInput.trim()) return;

    let chatbox = document.getElementById("chatbox");
    chatbox.innerHTML += `<p><strong>You:</strong> ${userInput}</p>`;  // Corrected with backticks

    fetch("chat.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: userInput })
    })
    .then(response => response.json())
    .then(data => {
        let reply = data.choices?.[0]?.message?.content || "Error in response.";  // Make sure API returns the right structure
        chatbox.innerHTML += `<p><strong>AI:</strong> ${reply}</p>`;  // Corrected with backticks
        chatbox.scrollTop = chatbox.scrollHeight;
    })
    .catch(error => console.error("Error:", error));

    document.getElementById("userInput").value = "";
}