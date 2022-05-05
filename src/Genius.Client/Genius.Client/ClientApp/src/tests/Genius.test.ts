import {
  Genius,
  ExpertSystem,
  IExpertSystem,
  ExpertCondition,
  IExpertCondition,
  ExpertProduct,
  IExpertProduct,
  IImportRequest,
  ImportRequest
} from '../genius/Genius';

test('API correctly fetches the system based on the GUID', async () => {
  const EXPERT_GUID = '268700b4-eb00-4175-a71e-eb6fc40f804a';

  let system = await Genius.Api.getSystemByGuid(EXPERT_GUID);

  console.debug(system);

  expect(system).toBeInstanceOf(ExpertSystem);
});
