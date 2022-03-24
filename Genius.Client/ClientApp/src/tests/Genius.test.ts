import GeniusApi from '../genius/GeniusApi';
import IExpertSystem from '../genius/interfaces/IExpertSystem';
import ExpertSystem from '../genius/ExpertSystem';

test('API correctly fetches the system based on the GUID', async () => {
  const EXPERT_GUID = '268700b4-eb00-4175-a71e-eb6fc40f804a';

  let system = await GeniusApi.getSystemByGuid(EXPERT_GUID);

  console.debug(system);

  expect(system).toBeInstanceOf(ExpertSystem);
});
