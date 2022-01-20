// This Source Code Form is subject to the terms of the GNU GPL-3.0 License.
// If a copy of the GPL-3.0 was not distributed with this file, You can obtain one at https://www.gnu.org/licenses/gpl-3.0.en.html.
// Copyright (C) 2022 Leszek Pomianowski.
// All Rights Reserved.

using GeniusProtocol;
using Grpc.Core;
using Microsoft.Extensions.Logging;
using System.Threading.Tasks;

namespace Genius.Services
{
    public class GrpcStatisticsServer : Statistic.StatisticBase
    {
        private readonly ILogger<GrpcStatisticsServer> _logger;

        public GrpcStatisticsServer(ILogger<GrpcStatisticsServer> logger)
        {
            _logger = logger;
        }

        public override Task<StatisticResponseModel> Push(StatisticModel request, ServerCallContext context)
        {
            return base.Push(request, context);
        }

        public override Task Get(StatisticLookupModel request, IServerStreamWriter<StatisticModel> responseStream, ServerCallContext context)
        {
            return base.Get(request, responseStream, context);
        }
    }
}
