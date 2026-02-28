function ManageRequest(action, acc1, acc2) {
    // Retrieve values using getElementById
    var IDAccount1 = acc1;
    var IDAccount2 = acc2;

    // Prepare data to be sent via AJAX
    var formData = {
        IDAccount1: IDAccount1,
        IDAccount2: IDAccount2,
        Action: action
    };

    $.ajax({
        url: 'ajx/ajxManageFriendRequest.php',
        type: 'POST',
        data: formData, // Send data to the server
        success: function (response) {
            // Handle the response from the server
            alert(response);
        },
        error: function (xhr, status, error) {
            // Handle errors
            alert('An error occurred: ' + error);
        }
    });
}
// function addFriend(id1, id2) {
//     var idAccount1 = id1;
//     var idAccount2 = id2;

//     var xhr = new XMLHttpRequest();
//     xhr.open('POST', 'ajx/ajxSendFriendRequest.php', true);
//     xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === XMLHttpRequest.DONE) {
//             if (xhr.status === 200) {
//                 alert('Friend request sent successfully!');
                
//                 var id = id1.toString() + id2.toString();
//                 console.log('Constructed ID:', id);

//                 var btn = document.getElementById(id);
//                 console.log('Button Element:', btn);
                
//                 if (btn) {
//                     btn.className = 'button-sent';
//                     console.log('Class updated to button-sent');
//                 } else {
//                     console.error('Element with ID "' + id + '" not found.');
//                 }
//             } else {
//                 console.error('Error sending friend request. Status:', xhr.status, 'Response:', xhr.responseText);
//                 alert('Error sending friend request.');
//             }
//         }
//     };

//     xhr.send('idAccount1=' + encodeURIComponent(idAccount1) + '&idAccount2=' + encodeURIComponent(idAccount2));
// }