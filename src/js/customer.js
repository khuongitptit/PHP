var firebaseConfig = {
  apiKey: "AIzaSyAWL4KX-Cz-jWCtPnai1jdrF_kz-9anlBk",
  authDomain: "php1-1ec2d.firebaseapp.com",
  projectId: "php1-1ec2d",
  storageBucket: "php1-1ec2d.appspot.com",
  messagingSenderId: "659957794318",
  appId: "1:659957794318:web:11244e454b01718e375e5f",
};
firebase.initializeApp(firebaseConfig);
var storage = firebase.storage();

const getAll = () => {
  console.log("getall")
  $.ajax({
    type: 'GET',
    url: 'src/php/customer.php',
    data: {action:'GET_ALL'},
    success: res => renderCustomer(res),
    error: err => {
      console.log('err', err);
    },
  });
}
const postCustomer = () => {
  console.log("post");
  //first, upload avatarIMG to firebase and get URL
  let avatarFile = $("input[name=avatarUpload]").prop("files")[0];
  const storageRef = storage.ref(`/php_img/${avatarFile.name}`);
  storageRef.put(avatarFile).then(() => {
    storageRef.getDownloadURL().then((avatarURL) => {
      //post to php to process
      let name = $("#name").val();
      let dob = $("#dob").val();
      let gender = $("input[name=gender]:checked").val();
      let address = $("#address").val();
      let phone = $("#phone").val();
      let email = $("#email").val();
      $.ajax({
        type: "POST",
        url: "src/php/customer.php",
        data: {
          action:'ADD',
          name,
          dob,
          gender,
          avatarURL,
          address,
          phone,
          email,
        },
        success: (res) => {
          if(JSON.parse(res).success){
            getAll();
            $("#addCustomerModal").hide();
          }
        },
        error: (err) => {
          console.log("err", err);
        },
      });
    });
  });
};
//renderCustomer
const renderCustomer = (res) => {
  const customerListData = JSON.parse(res);
  console.log("data", customerListData);
  const customerTable = $("#customer-table-body");
  customerTable.empty();
  _.map(customerListData, (customer) => {
    let tableRow = $(
      `<tr id=${customer.id}>
        <td>${customer.name}</td>
        <td><img src=${customer.avatarURL} alt="avatar" class="avatar"/></td>
        <td>${customer.dob}</td>
        <td>${customer.gender == 1 ? "Nam" : "Nữ"}</td>
        <td>${customer.address}</td>
        <td>${customer.phone}</td>
        <td>${customer.email}</td>
        <td>
          <a class="editBtn" data-toggle="modal" data-target="#addCustomerModal" customerId=${customer.id}>
            <i class="fas fa-edit"></i>
          </a>
          <a class="deleteBtn" data-toggle="modal" data-target="#deleteModal" customerId=${customer.id}>
            <span class="material-icons">delete</span>
          </a>
          <a class="viewCommentBtn" data-toggle="modal" data-target="#commentModal" customerId=${customer.id}>
            <span class="material-icons">chat</span>
          </a>
        </td>
      </tr>`
    );
    customerTable.append(tableRow);
  });
};
//=====================deleteCustomer=================  
const deleteCustomer = customerId => {
  $.ajax({
    type: "POST",
    url: "src/php/customer.php",
    data: {
      action:'DELETE',
      customerId
    },
    success: (res) => {
      const deletedId = "#"+customerId;
      const deletedRow = $(deletedId);
      deletedRow.remove();
    },
    error: (err) => {
      console.log("err", err);
    },
  });
}
//==================editCustomer===================
const editCustomer = (customerId,avatarURL) => {
  //first, upload avatarIMG to firebase and get URL
  let avatarFile = $("input[name=avatarUpload]").prop("files")[0];
  if(avatarFile){
    const storageRef = storage.ref(`/php_img/${avatarFile.name}`);
    storageRef.put(avatarFile).then(() => {
      storageRef.getDownloadURL().then((avatarURL) => {
        //post to php to process
        let name = $("#name").val();
        let dob = $("#dob").val();
        let gender = $("input[name=gender]:checked").val();
        let address = $("#address").val();
        let phone = $("#phone").val();
        let email = $("#email").val();
        console.log("post",{
          action:'UPDATE',
          customerId,
          name,
          dob,
          gender,
          avatarURL,
          address,
          phone,
          email,
        },)
        $.ajax({
          type: "POST",
          url: "src/php/customer.php",
          data: {
            action:'UPDATE',
            customerId,
            name,
            dob,
            gender,
            avatarURL,
            address,
            phone,
            email,
          },
          success: (res) => {
            console.log("res",res)
            if(JSON.parse(res).success){
              getAll();
              $("#addCustomerModal").hide();
            }
          },
          error: (err) => {
            console.log("err", err);
          },
        });
      });
    });
  }else {
    let name = $("#name").val();
    let dob = $("#dob").val();
    let gender = $("input[name=gender]:checked").val();
    let address = $("#address").val();
    let phone = $("#phone").val();
    let email = $("#email").val();
    console.log("edit",{
      action:'UPDATE',
      customerId,
      name,
      dob,
      gender,
      avatarURL,
      address,
      phone,
      email,
    },)
    $.ajax({
      type: "POST",
      url: "src/php/customer.php",
      data: {
        action:'UPDATE',
        customerId,
        name,
        dob,
        gender,
        avatarURL,
        address,
        phone,
        email,
      },
      success: (res) => {
        if(JSON.parse(res).success){
          getAll();
          $("#addCustomerModal").hide();
        }
      },
      error: (err) => {
        console.log("err", err);
      },
    });
  }
};

