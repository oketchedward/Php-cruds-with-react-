import React, { useEffect, useState } from 'react'
import axios from 'axios';
import {Link} from 'react-router-dom';
function ListUser() {

    const [users, setUsers] = useState([]);
      useEffect(() => {
        getUsers();   
    }, []);

  function getUsers(){
    axios.get('http://localhost:80/api/users/').then(function(response){
          console.log(response.data);
          setUsers(response.data);
      });   
      
  }
  const deleteUser = (id) => {
    axios.delete(`http://localhost:80/api/user/${id}/delete`).then(function(response){
      console.log(response.data);
      getUsers(); 
    })
  }
  
  return (
    <div className="flex justify-center items-center">
      <div className=" lg:w-[75rem]">
        <div className="flex justify-center items-center mb-3">
            <h1 className='text-teal-500 text-2xl font-semibold'>User's List</h1>
        </div>
      <table className="table table-bordered table-striped font-semibold">
        <thead className='text-teal-600'>
          <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {Array.isArray(users) && users.map((user, key) => 
              <tr key={user.id}>
                <td>{user.id}</td>
                <td>{user.Username}</td>
                <td>{user.email}</td>
                <td>{user.mobile}</td>
                <td className='gap-10'>
                  <Link to={`user/${user.id}/edit`} className='btn btn-success w-20' style={{marginRight: "10px"}} >Edit</Link>
                  <button onClick={() => deleteUser(user.id)} className="btn btn-danger w-20">Delete</button>
                </td>
              </tr>
          )}
        </tbody>
      </table>
    </div>
    </div>

  )
}

export default ListUser
