// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using Genius.Client.Export;
using Genius.Client.Services;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using System.Threading.Tasks;

namespace Genius.Client.Controllers
{
    [ApiController]
    [Route("api/export")]
    public class ExportController : ControllerBase
    {
        private readonly ILogger<ExportController> _logger;

        private readonly GrpcExpertClientService _expertClient;

        public ExportController(ILogger<ExportController> logger, GrpcExpertClientService expertClient)
        {
            _logger = logger;
            _expertClient = expertClient;
        }

        [HttpGet]
        [Route("{guid}")]
        public async Task<IActionResult> GetSingleSystem([FromRoute] string guid)
        {
            var expertData = await _expertClient.GetSystemByGuidAsync(guid);

            if (expertData == null || expertData.Id < 1)
                return NotFound();

            var exportModel = new ExportExpertModel
            {
                System = expertData,
                Relations = await _expertClient.GetSystemRelationsAsync(expertData.Id),
                Products = await _expertClient.GetSystemProductsAsync(expertData.Id),
                Conditions = await _expertClient.GetSystemConditionsAsync(expertData.Id),
            };

            return new SystemToFileResult(exportModel);
        }
    }
}