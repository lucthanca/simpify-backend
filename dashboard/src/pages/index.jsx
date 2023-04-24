import { useNavigate } from 'react-router-dom';
import { useEffect } from 'react';

export default function HomePage() {
  const navigate = useNavigate();
  useEffect(() => {
    console.log('heheh');
    navigate('/dashboard', { replace: true });
  }, []);
  return null;
}
