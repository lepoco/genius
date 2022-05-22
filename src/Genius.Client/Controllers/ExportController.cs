// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using System.Collections.Generic;
using System.Threading.Tasks;
using Genius.Client.Export;
using Genius.Client.Interfaces;
using GeniusProtocol;
using Grpc.Core;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;

namespace Genius.Client.Controllers;

/// <summary>
/// Provides API for exporting expert systems to files.
/// </summary>
[ApiController]
[Route("api/export")]
public class ExportController : ControllerBase
{
    private readonly ILogger<ExportController> _logger;

    private readonly Expert.ExpertClient _grpcClient;

    //private readonly GrpcExpertClientService _expertClient;

    public ExportController(ILogger<ExportController> logger, IChannel channel)
    {
        _logger = logger;
        _grpcClient = channel.GetClient<Expert.ExpertClient>();
    }

    [HttpGet]
    [Route("{guid}")]
    public async Task<IActionResult> GetSingleSystem([FromRoute] string guid)
    {
        var expertData = await _grpcClient.GetAsync(new ExpertLookupModel { Guid = guid });

        if (expertData == null || expertData.Id < 1)
            return NotFound();

        var systemRelations = new List<RelationModel>();

        using var relCall = _grpcClient.GetSystemRelations(new ExpertLookupModel { Id = expertData.Id });

        while (await relCall.ResponseStream.MoveNext())
            systemRelations.Add(relCall.ResponseStream.Current);

        relCall.Dispose();

        var systemProducts = new List<ProductModel>();

        using var prodCall = _grpcClient.GetSystemProducts(new ExpertLookupModel { Id = expertData.Id });

        while (await prodCall.ResponseStream.MoveNext())
            systemProducts.Add(prodCall.ResponseStream.Current);

        prodCall.Dispose();

        var systemConditions = new List<ConditionModel>();

        using var conCall = _grpcClient.GetSystemConditions(new ExpertLookupModel { Id = expertData.Id });

        while (await conCall.ResponseStream.MoveNext())
            systemConditions.Add(conCall.ResponseStream.Current);

        conCall.Dispose();

        var exportModel = new ExportExpertModel
        {
            System = expertData,
            Relations = systemRelations,
            Products = systemProducts,
            Conditions = systemConditions,
        };

        return new SystemToFileResult(exportModel);
    }
}
