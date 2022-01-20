import React from 'react'
import ReactDOM from 'react-dom'
import { MemoryRouter } from 'react-router-dom'
import App from './App'

it('renders without crashing', async () => {
  ReactDOM.render(
    <MemoryRouter>
      <App />
    </MemoryRouter>,
    document.createElement('div')
  )
  await new Promise((resolve) => setTimeout(resolve, 1000))
})
