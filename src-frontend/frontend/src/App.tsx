import React, {useState} from 'react';

import "./App.css";

import Admin from './Views/Admin';
import User from './Views/User';

function App() {
  const [isShowAdmin, setIsShowAdmin] = useState<Boolean>(false);

  const showAdminHandler = () => {
    setIsShowAdmin(true);
  }

  const showUserHandler = () => {
    setIsShowAdmin(false);
  }

  return (
    <div className="App">
      <button onClick={showAdminHandler}>Admin</button>
      <button onClick={showUserHandler}>User</button>

      {isShowAdmin && <Admin />}
      {!isShowAdmin && <User />}
    </div>
  );
}

export default App;
