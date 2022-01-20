import { NavigateFunction, Params, Location } from 'react-router-dom';

export default interface IRouter {
  location: Location;
  navigate: NavigateFunction;
  params: Params<any>;
}
