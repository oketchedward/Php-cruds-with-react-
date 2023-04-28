import React, { useState, useEffect } from 'react'
import axios from 'axios';
import { useNavigate, useParams } from 'react-router-dom';

export default function EditUser() {
  const [inputs, setInputs] = useState({
   
  });

    const {id} = useParams();

    useEffect(() => {
      const getUser = () => {
        axios.get(`http://localhost:80/api/user/${id}`).then(function(response){
          console.log(response.data);
          setInputs(response.data);
        }); 
      }
      getUser();
    }, [id]);

    
    const navigate = useNavigate();
  
    const handleChange = (event) => {
    const name = event.target.name;
    const value = event.target.value;

    setInputs(values => ({ ...values, [name]: value }));
  }

  const handleFileChange = (event) => {
    const name = event.target.name;
    const file = event.target.files[0];

      setInputs(values => ({ ...values, [name]: file }));

  };

  const handleSubmit = (event) => {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('username', inputs.username);
    formData.append('id', inputs.id);
    formData.append('email', inputs.email);
    formData.append('mobile', inputs.mobile);
    formData.append('image', inputs.image);

    console.log(...formData);

    axios.put(`http://localhost:80/api/user/${id}/edit`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data' // Set the content type for FormData
      }
    }).then(function(response){
      console.log(response.data);
      navigate('/');
    })
    .catch(function(error) {
      console.error('Error:', error);
    });
    
  }
  return (
<div className="flex justify-center items-center lg:w-[100rem] pt-3"> 
<div className="w-screen max-w-xs "> 
<div className="flex justify-center">
<h1 className='text-2xl text-teal-800 font-medium'>Edit User</h1> 
</div>
      <form onSubmit={handleSubmit} enctype="multipart/form-data" className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 w-[25rem]">
          <div className="mb-3">
            <input name="id" defaultValue={inputs.id} hidden />
            <label className="block text-gray-700 text-sm font-bold mb-2">Name: </label>  
              <input type="text" defaultValue={inputs.username} className="shadow appearance-none border rounded w-full py-3 px-7 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="username" onChange={handleChange} />  
          </div>
              <div className="mb-3">
              <label className="block text-gray-700 text-sm font-bold mb-2">Email: </label>   
              <input type="text" defaultValue={inputs.email} className="shadow appearance-none border rounded w-full py-3 px-7 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="email" onChange={handleChange} />
              </div>
            
              <div className="block text-gray-700 text-sm font-bold mb-2">           
              <label htmlFor="">Mobile: </label> 
              <input defaultValue={inputs.mobile} type="text"className="shadow appearance-none border rounded w-full py-3 px-7 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" name="mobile" onChange={handleChange} />
              </div>

              <div className='mb-3'>           
                    <label className="block text-gray-700 text-sm font-bold mb-2">File: </label> 
                    <input type="file" defaultValue={inputs.image} name="image" onChange={handleFileChange}  className="shadow appearance-none border rounded w-full py-3 px-7 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  />
                  </div>

              <button type='submit' name='add' className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Confirm</button>
              
              </form>
          </div>
          </div>
          )
}

 
