let donors = JSON.parse(localStorage.getItem("donors")) || [];

document.getElementById("donorForm")?.addEventListener("submit", function(event) {
    event.preventDefault();

    let name = document.getElementById("name").value;
    let age = document.getElementById("age").value;
    let bloodGroup = document.getElementById("bloodGroup").value;
    let contact = document.getElementById("contact").value;

    let donor = { name, age, bloodGroup, contact };
    donors.push(donor);
    localStorage.setItem("donors", JSON.stringify(donors));
    alert("Donor Registered Successfully!");

    document.getElementById("donorForm").reset();
});

function searchBlood() {
    let searchBloodGroup = document.getElementById("searchBloodGroup").value;
    let donorList = document.getElementById("donorList");
    donorList.innerHTML = "";

    let filteredDonors = donors.filter(donor => donor.bloodGroup === searchBloodGroup);

    if (filteredDonors.length > 0) {
        filteredDonors.forEach(donor => {
            let listItem = document.createElement("li");
            listItem.innerHTML = `${donor.name}, Age: ${donor.age}, Contact: ${donor.contact}`;
            donorList.appendChild(listItem);
        });
    } else {
        donorList.innerHTML = "<li>No donors available.</li>";
    }
}

function loadAdminDonors() {
    let adminDonorList = document.getElementById("adminDonorList");
    if (!adminDonorList) return;
    
    adminDonorList.innerHTML = "";
    donors.forEach((donor, index) => {
        let row = `<tr>
            <td>${donor.name}</td>
            <td>${donor.age}</td>
            <td>${donor.bloodGroup}</td>
            <td>${donor.contact}</td>
            <td><button class="delete-btn" onclick="deleteDonor(${index})">Delete</button></td>
        </tr>`;
        adminDonorList.innerHTML += row;
    });
}

function deleteDonor(index) {
    donors.splice(index, 1);
    localStorage.setItem("donors", JSON.stringify(donors));
    loadAdminDonors();
}

document.addEventListener("DOMContentLoaded", loadAdminDonors);