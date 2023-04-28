import React from 'react';
import './App.css';
import { BrowserRouter, Routes, Route, Link   } from 'react-router-dom';
import CreateUser from './components/CreateUser';
import ListUser from './components/ListUser';
import EditUser from './components/EditUser';
function App() {
  return (
    <div className="w-screen">
      <div className="flex justify-center items-center pt-10">
          <h5 className='text-3xl text-teal-600 font-bold lg:m-3'>React CRUD Operations using PHP API and MYSQL.</h5>
      </div>
     <BrowserRouter>
     <nav className='flex justify-center items-center'>
      <ul className='p-2'>
        <li>
          <Link to="/" className='btn btn-secondary mx-10'>List Users</Link>
        </li>
        <li>
          <Link to="user/create" className="btn btn-success">Create User</Link>
        </li>
      </ul>
     </nav>
      <Routes>
        <Route index element={<ListUser />}/>
        <Route path="user/create" element={<CreateUser />}/>
        <Route path="user/:id/edit" element={<EditUser />}/>
      </Routes>
     </BrowserRouter>
    </div>
  );
}

export default App;
